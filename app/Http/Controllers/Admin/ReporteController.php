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
use Carbon\Carbon;
use App\Models\LecturaSensor;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

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
        $users = collect(); // Inicializamos la colección de usuarios

        if ($user->role->nombre_rol === 'Admin') {
            // El Admin ve TODOS los módulos y los usuarios que tienen viveros
            $modulos = Modulo::with('vivero')->orderBy('vivero_id')->orderBy('codigo_identificador')->get();
            $modulosOcupados = $modulos->where('estado', 'Ocupado');
            // Obtenemos solo usuarios que tienen al menos un vivero para la selección
            $users = User::whereHas('viveros')->orderBy('nombres')->get();

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
        }

        return view('admin.reportes.module-form', compact('modulos', 'modulosOcupados', 'users'));
    }

    

    /**
     * Muestra la previsualización del reporte de módulo en la web.
     */
    public function showModuleReportPreview(Request $request)
    {
        // --- VALIDACIÓN CORREGIDA ---
        $validated = $request->validate([
            'report_type' => 'required|string|in:custom,cultivo',
            // modulo_id es requerido SI report_type es 'cultivo'
            'modulo_id' => ['required_if:report_type,cultivo', Rule::exists('modulos', 'id')],
            // modulo_id_custom es requerido SI report_type es 'custom'
            'modulo_id_custom' => ['required_if:report_type,custom', Rule::exists('modulos', 'id')],
            // fecha_inicio es requerido SI report_type es 'custom'
            'fecha_inicio' => 'required_if:report_type,custom|nullable|date',
            // fecha_fin es requerido SI report_type es 'custom'
            'fecha_fin' => 'required_if:report_type,custom|nullable|date|after_or_equal:fecha_inicio',
        ]);

        // --- LÓGICA AUTOMÁTICA DE FECHAS ---
        if ($request->input('report_type') === 'cultivo') {
            $moduloId = $validated['modulo_id'];
            $modulo = Modulo::findOrFail($moduloId);
            // Si falta la fecha de siembra, usamos los últimos 30 días como fallback
            $fechaInicio = $modulo->fecha_siembra ? Carbon::parse($modulo->fecha_siembra)->startOfDay() : Carbon::now()->subDays(29)->startOfDay();
            $fechaFin = Carbon::now()->endOfDay(); // Hasta el día de hoy
        } else {
            // Usamos las fechas y el módulo_id del rango personalizado
            $moduloId = $validated['modulo_id_custom'];
            $modulo = Modulo::findOrFail($moduloId); // Aseguramos que $modulo esté definido
            $fechaInicio = Carbon::parse($validated['fecha_inicio'])->startOfDay();
            $fechaFin = Carbon::parse($validated['fecha_fin'])->endOfDay();
        }
        // --- FIN LÓGICA AUTOMÁTICA ---


        // Obtener módulo y verificar permisos (igual que antes, pero $modulo ya está cargado)
        $user = Auth::user();
        $esAdmin = $user->role->nombre_rol === 'Admin';
        $esDuenoDelModulo = !$esAdmin && $user->id === $modulo->vivero->user_id;
        if (!$esAdmin && !$esDuenoDelModulo) {
            abort(403, 'No tienes permiso para ver reportes para este módulo.');
        }

        // Obtener datos reales (consulta agregada por hora - igual que antes)
        $lecturasAgregadas = LecturaSensor::where('modulo_id', $moduloId)
            ->whereBetween('created_at', [$fechaInicio, $fechaFin])
            ->select(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d %H:00:00") as hora_grupo'), DB::raw('AVG(ph) as ph_avg'), DB::raw('MIN(ph) as ph_min'), DB::raw('MAX(ph) as ph_max'), DB::raw('AVG(ec) as ec_avg'), DB::raw('MIN(ec) as ec_min'), DB::raw('MAX(ec) as ec_max'), DB::raw('AVG(temperatura) as temperatura_avg'), DB::raw('MIN(temperatura) as temperatura_min'), DB::raw('MAX(temperatura) as temperatura_max'), DB::raw('AVG(luz) as luz_avg'), DB::raw('MIN(luz) as luz_min'), DB::raw('MAX(luz) as luz_max'), DB::raw('AVG(humedad) as humedad_avg'), DB::raw('MIN(humedad) as humedad_min'), DB::raw('MAX(humedad) as humedad_max'))
            ->groupBy('hora_grupo')
            ->orderBy('hora_grupo', 'asc')
            ->get();

        // Preparar datos para la vista (igual que antes)
        $data = [
            'modulo' => $modulo,
            'fechaInicio' => $fechaInicio->isoFormat('D MMMM YYYY'),
            'fechaFin' => $fechaFin->isoFormat('D MMMM YYYY'),
            'lecturas' => $lecturasAgregadas,
            'dueno' => $modulo->vivero->user,
            'generadoPor' => $user->full_name,
            'fechaGeneracion' => Carbon::now()->isoFormat('D MMMM YYYY, H:mm'),
            'request' => $request,
        ];

        // Devolver la vista de previsualización
        return view('admin.reportes.module-report', $data);
    }

    /**
     * Genera y descarga el reporte PDF para un módulo específico.
     */
    public function generateModuleReport(Request $request)
    {
        // --- VALIDACIÓN CORREGIDA (Igual que en showModuleReportPreview) ---
         $validated = $request->validate([
            'report_type' => 'required|string|in:custom,cultivo',
            'modulo_id' => ['required_if:report_type,cultivo', Rule::exists('modulos', 'id')],
            'modulo_id_custom' => ['required_if:report_type,custom', Rule::exists('modulos', 'id')],
            'fecha_inicio' => 'required_if:report_type,custom|nullable|date',
            'fecha_fin' => 'required_if:report_type,custom|nullable|date|after_or_equal:fecha_inicio',
        ]);

        // --- LÓGICA AUTOMÁTICA DE FECHAS (Igual que en showModuleReportPreview) ---
         if ($request->input('report_type') === 'cultivo') {
            $moduloId = $validated['modulo_id'];
            $modulo = Modulo::findOrFail($moduloId);
            $fechaInicio = $modulo->fecha_siembra ? Carbon::parse($modulo->fecha_siembra)->startOfDay() : Carbon::now()->subDays(29)->startOfDay();
            $fechaFin = Carbon::now()->endOfDay();
        } else {
            $moduloId = $validated['modulo_id_custom'];
             $modulo = Modulo::findOrFail($moduloId);
            $fechaInicio = Carbon::parse($validated['fecha_inicio'])->startOfDay();
            $fechaFin = Carbon::parse($validated['fecha_fin'])->endOfDay();
        }
        // --- FIN LÓGICA AUTOMÁTICA ---

        // Obtener módulo y verificar permisos (igual que antes)
        $user = Auth::user();
        $esAdmin = $user->role->nombre_rol === 'Admin';
        $esDuenoDelModulo = !$esAdmin && $user->id === $modulo->vivero->user_id;
        if (!$esAdmin && !$esDuenoDelModulo) {
            abort(403, 'No tienes permiso para generar reportes para este módulo.');
        }

        // Obtener datos reales (consulta agregada por hora - igual que antes)
        $lecturasAgregadas = LecturaSensor::where('modulo_id', $moduloId)
            ->whereBetween('created_at', [$fechaInicio, $fechaFin])
             ->select(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d %H:00:00") as hora_grupo'), DB::raw('AVG(ph) as ph_avg'), DB::raw('MIN(ph) as ph_min'), DB::raw('MAX(ph) as ph_max'), DB::raw('AVG(ec) as ec_avg'), DB::raw('MIN(ec) as ec_min'), DB::raw('MAX(ec) as ec_max'), DB::raw('AVG(temperatura) as temperatura_avg'), DB::raw('MIN(temperatura) as temperatura_min'), DB::raw('MAX(temperatura) as temperatura_max'), DB::raw('AVG(luz) as luz_avg'), DB::raw('MIN(luz) as luz_min'), DB::raw('MAX(luz) as luz_max'), DB::raw('AVG(humedad) as humedad_avg'), DB::raw('MIN(humedad) as humedad_min'), DB::raw('MAX(humedad) as humedad_max'))
            ->groupBy('hora_grupo')
            ->orderBy('hora_grupo', 'asc')
            ->get();

        // Preparar datos para la vista PDF (igual que antes)
        $data = [
            'modulo' => $modulo,
            'fechaInicio' => $fechaInicio->isoFormat('D MMMM YYYY'),
            'fechaFin' => $fechaFin->isoFormat('D MMMM YYYY'),
            'lecturas' => $lecturasAgregadas,
            'dueno' => $modulo->vivero->user,
            'generadoPor' => $user->full_name,
            'fechaGeneracion' => Carbon::now()->isoFormat('D MMMM YYYY, H:mm'),
            'limits' => config('hydroponics.limits'), // Añadimos los límites de los sensores
            'cultivoActual' => $modulo->cultivo_actual, // Añadimos el cultivo actual
            'fechaSiembra' => $modulo->fecha_siembra, // Añadimos la fecha de siembra
        ];

        // Generar y descargar el PDF
        $pdf = Pdf::loadView('admin.reportes.module-report-pdf', $data)->setPaper('a4', 'landscape');
        $fileName = 'Reporte_Horario_' . $modulo->codigo_identificador . '_' . $fechaInicio->format('Ymd') . '-' . $fechaFin->format('Ymd') . '.pdf';
        return $pdf->download($fileName);
    }


    // --- REPORTE DE VIVEROS ---

    public function showViverosReport(Request $request)
    {
        // Lógica de cálculo de alertas
        $PH_MIN = 5.8;
        $PH_MAX = 6.4;
        $TEMP_MAX = 25.0;
        $OFFLINE_THRESHOLD_MINUTES = 10;

        // Obtener el ID de usuario del request para el filtro
        $userId = $request->input('user_id');

        // Empezar la consulta base para Viveros
        $viveroQuery = Vivero::with('user', 'modulos.latestLectura');

        // Si se especifica un userId, filtrar por ese usuario
        if ($userId) {
            $viveroQuery->where('user_id', $userId);
        }

        // Ejecutar la consulta (filtrada o no)
        $viveros = $viveroQuery->get();

        // Iterar sobre cada vivero para calcular las métricas
        foreach ($viveros as $vivero) {
            $modulosOcupados = 0;
            $alertasAdvertencia = 0;
            $alertasCritico = 0;
            $modulosOffline = 0;

            foreach ($vivero->modulos as $modulo) {
                if ($modulo->estado === 'Ocupado') {
                    $modulosOcupados++;
                }

                $latestReading = $modulo->latestLectura;

                if ($latestReading) {
                    $diffInMinutes = now()->diffInMinutes($latestReading->created_at);

                    if ($diffInMinutes > $OFFLINE_THRESHOLD_MINUTES) {
                        $modulosOffline++;
                        continue; 
                    }

                    $phVal = (float)$latestReading->ph;
                    $tempVal = (float)$latestReading->temperatura;

                    if ($phVal < ($PH_MIN - 0.5) || $phVal > ($PH_MAX + 0.5)) {
                        $alertasCritico++;
                    } elseif ($phVal < $PH_MIN || $phVal > $PH_MAX || $tempVal > $TEMP_MAX) {
                        $alertasAdvertencia++;
                    }

                } else {
                    if ($modulo->estado !== 'Disponible') {
                        $modulosOffline++;
                    }
                }
            }

            // Adjuntar los contadores calculados al objeto vivero
            $vivero->modulos_total_count = $vivero->modulos->count();
            $vivero->modulos_ocupados_count = $modulosOcupados;
            $vivero->alertas_advertencia_count = $alertasAdvertencia;
            $vivero->alertas_critico_count = $alertasCritico;
            $vivero->modulos_offline_count = $modulosOffline;
        }

        // Obtener todos los usuarios que tienen viveros para el menú de filtro
        $users = User::whereHas('viveros')->orderBy('nombres')->get();

        return view('admin.reportes.viveros-report', compact('viveros', 'users'));
    }

    public function downloadViverosReport(Request $request)
    {
        // Lógica de cálculo de alertas
        $PH_MIN = 5.8;
        $PH_MAX = 6.4;
        $TEMP_MAX = 25.0;
        $OFFLINE_THRESHOLD_MINUTES = 10;

        // Obtener el ID de usuario del request para el filtro
        $userId = $request->input('user_id');
        $filteredUser = null;

        // Empezar la consulta base para Viveros
        $viveroQuery = Vivero::with('user', 'modulos.latestLectura');

        // Si se especifica un userId, filtrar y obtener el usuario
        if ($userId) {
            $viveroQuery->where('user_id', $userId);
            $filteredUser = User::find($userId);
        }

        $viveros = $viveroQuery->get();

        // Iterar sobre cada vivero para calcular las métricas
        foreach ($viveros as $vivero) {
            $modulosOcupados = 0;
            $alertasAdvertencia = 0;
            $alertasCritico = 0;
            $modulosOffline = 0;

            foreach ($vivero->modulos as $modulo) {
                if ($modulo->estado === 'Ocupado') {
                    $modulosOcupados++;
                }
                $latestReading = $modulo->latestLectura;
                if ($latestReading) {
                    $diffInMinutes = now()->diffInMinutes($latestReading->created_at);
                    if ($diffInMinutes > $OFFLINE_THRESHOLD_MINUTES) {
                        $modulosOffline++;
                        continue;
                    }
                    $phVal = (float)$latestReading->ph;
                    $tempVal = (float)$latestReading->temperatura;
                    if ($phVal < ($PH_MIN - 0.5) || $phVal > ($PH_MAX + 0.5)) {
                        $alertasCritico++;
                    } elseif ($phVal < $PH_MIN || $phVal > $PH_MAX || $tempVal > $TEMP_MAX) {
                        $alertasAdvertencia++;
                    }
                } else {
                    if ($modulo->estado !== 'Disponible') {
                        $modulosOffline++;
                    }
                }
            }
            $vivero->modulos_total_count = $vivero->modulos->count();
            $vivero->modulos_ocupados_count = $modulosOcupados;
            $vivero->alertas_advertencia_count = $alertasAdvertencia;
            $vivero->alertas_critico_count = $alertasCritico;
            $vivero->modulos_offline_count = $modulosOffline;
        }

        $data = [ 
            'viveros' => $viveros, 
            'fecha' => now()->isoFormat('D MMMM YYYY'),
            'filteredUser' => $filteredUser
        ];
        $pdf = Pdf::loadView('admin.reportes.viveros-report-pdf', $data);
        return $pdf->download('reporte-general-viveros.pdf');
    }
}

