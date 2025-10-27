<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Modulo;
use App\Models\Vivero;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\CarbonPeriod;
use App\Models\LecturaSensor;

class ReporteController extends Controller
{
    // --- REPORTE DE MÓDULO ---

    /**
     * Muestra el formulario para generar el reporte de un módulo.
     * Filtra los módulos disponibles según el rol del usuario.
     */
    public function showModuleReportForm()
    {
        $user = Auth::user();
        $modulos = collect(); 
        $modulosOcupados = collect(); 

        if ($user->role->nombre_rol === 'Admin') {
            // El Admin ve TODOS los módulos
            $modulos = Modulo::with('vivero')->orderBy('vivero_id')->orderBy('codigo_identificador')->get();
            $modulosOcupados = $modulos->where('estado', 'Ocupado');

        } else { 
            // --- LÓGICA CORREGIDA PARA VARIOS VIVEROS ---
            // 1. Obtenemos la COLECCIÓN de viveros del dueño
            $viverosDelDueno = $user->viveros()->with(['modulos' => function ($query) {
                // Cargamos los módulos de cada vivero, ordenados
                $query->with('vivero')->orderBy('codigo_identificador'); 
            }])->get();

            // 2. Iteramos sobre cada vivero para recolectar sus módulos
            if ($viverosDelDueno) {
                foreach ($viverosDelDueno as $vivero) {
                    // Añadimos los módulos de este vivero a la colección general
                    $modulos = $modulos->merge($vivero->modulos);
                }
                // Filtramos los ocupados de la colección completa
                $modulosOcupados = $modulos->where('estado', 'Ocupado');
            }
            // Si $viverosDelDueno está vacío, $modulos quedará vacío (correcto)
        }

        // --- IMPORTANTE: Eliminamos o comentamos el dd() si aún estaba ---
        // dd($user->role->nombre_rol, $user->viveros, $modulos); 

        return view('admin.reportes.module-form', compact('modulos', 'modulosOcupados'));
    }
    

    /**
     * Muestra la previsualización del reporte de un módulo.
     */
    public function showModuleReportPreview(Request $request)
    {
        // Obtiene el tipo de reporte y el ID del módulo
        $reportType = $request->input('report_type');
        $moduloId = $request->input('report_type') === 'cultivo' ? $request->input('modulo_id') : $request->input('modulo_id_custom');

        // Validación inicial
        $request->validate([
            'report_type' => 'required|in:custom,cultivo',
        ]);

        // Validación condicional dependiendo del tipo de reporte
        if ($reportType === 'custom') {
            $request->validate([
                'modulo_id_custom' => 'required|exists:modulos,id',
                'fecha_inicio' => 'required|date',
                'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            ]);
            $fecha_inicio = $request->fecha_inicio;
            $fecha_fin = $request->fecha_fin;
        } else { // 'cultivo'
            $request->validate(['modulo_id' => 'required|exists:modulos,id']);
        }

        // Obtiene el módulo con sus relaciones
        $modulo = Modulo::with('vivero.user')->findOrFail($moduloId);

        // Si el reporte es por cultivo, determina las fechas automáticamente
        if ($reportType === 'cultivo') {
            $fecha_inicio = $modulo->fecha_siembra;
            $fecha_fin = now()->toDateString();
        }

        // Genera los datos falsos para el periodo
        $periodo = CarbonPeriod::create($fecha_inicio, $fecha_fin);
        $datos = [];
        foreach ($periodo as $fecha) {
            $datos[] = [
                'fecha' => $fecha->format('d/m/Y'),
                'temperatura' => rand(180, 250) / 10,
                'ph' => rand(55, 75) / 10,
                'ec' => rand(12, 25) / 10,
                'luz' => rand(700, 1200),
            ];
        }

        // Retorna la vista de previsualización con todos los datos necesarios
        return view('admin.reportes.module-report', compact('modulo', 'datos', 'request', 'fecha_inicio', 'fecha_fin'));
    }

    /**
     * Genera y descarga el PDF del reporte de un módulo.
     */
    public function generateModuleReport(Request $request)
    {
        // 1. Validar los filtros
        $validated = $request->validate([
            'modulo_id' => 'required|exists:modulos,id',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);

        $moduloId = $validated['modulo_id'];
        $fechaInicio = $validated['fecha_inicio'];
        $fechaFin = $validated['fecha_fin'];

        // 2. Obtener la información del módulo
        $modulo = Modulo::findOrFail($moduloId);
        $user = Auth::user(); // Obtenemos el usuario autenticado

        // --- INICIO DE LA VERIFICACIÓN DE PERMISOS ---
        // Verificamos si el usuario es Admin O si es el Dueño de este módulo específico.
        // Asumimos la relación User -> Vivero -> Modulos
        $esAdmin = $user->role->nombre_rol === 'Admin';
        $esDuenoDelModulo = false;
        if (!$esAdmin && $user->vivero && $user->vivero->id === $modulo->vivero_id) {
             $esDuenoDelModulo = true;
        }

        // Si NO es Admin Y TAMPOCO es el dueño, denegamos el acceso.
        if (!$esAdmin && !$esDuenoDelModulo) {
            // Usamos abort() para detener la ejecución y mostrar un error 403 (Prohibido)
            abort(403, 'No tienes permiso para generar reportes para este módulo.');
        }
        // --- FIN DE LA VERIFICACIÓN DE PERMISOS ---


        // 3. Obtener las lecturas (consulta real que ya teníamos)
        $lecturas = LecturaSensor::where('modulo_id', $moduloId)
            ->whereBetween('created_at', [$fechaInicio . ' 00:00:00', $fechaFin . ' 23:59:59'])
            ->orderBy('created_at', 'asc')
            ->get();

        // 4. Preparar los datos para la vista PDF
        $data = [
            'modulo' => $modulo,
            'fechaInicio' => $fechaInicio,
            'fechaFin' => $fechaFin,
            'lecturas' => $lecturas,
            'dueno' => $user->role->nombre_rol !== 'Admin' ? $user : $modulo->vivero->user, // Pasamos el dueño
        ];

        // 5. Generar y descargar el PDF
        $pdf = Pdf::loadView('admin.reportes.pdf.modulo_report', $data);
        $fileName = 'Reporte_Modulo_' . $modulo->codigo_identificador . '_' . $fechaInicio . '_a_' . $fechaFin . '.pdf';
        return $pdf->download($fileName);
    }


    // --- REPORTE DE VIVEROS ---

    public function showViverosReport()
    {
        $viveros = Vivero::with('user')->withCount('modulos')->get();
        return view('admin.reportes.viveros-report', compact('viveros'));
    }

    public function downloadViverosReport()
    {
        $viveros = Vivero::with('user')->withCount('modulos')->get();
        $data = [ 'viveros' => $viveros, 'fecha' => now()->format('d/m/Y') ];
        $pdf = Pdf::loadView('admin.reportes.viveros-report-pdf', $data);
        return $pdf->download('reporte-general-viveros.pdf');
    }
}

