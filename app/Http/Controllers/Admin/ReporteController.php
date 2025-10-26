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

class ReporteController extends Controller
{
    // --- REPORTE DE MÓDULO ---

    public function showModuleReportForm()
    {
        $user = Auth::user();
        $modulos = [];

        if ($user->role->nombre_rol == 'Admin') {
            $modulos = Modulo::with('vivero')->get();
        } else {
            $viveroIds = $user->viveros->pluck('id');
            $modulos = Modulo::whereIn('vivero_id', $viveroIds)->with('vivero')->get();
        }

        // Filtramos para obtener solo los módulos que tienen un cultivo activo
        $modulosOcupados = $modulos->where('estado', 'Ocupado');

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
        // La lógica es idéntica a la de la previsualización, pero el final es diferente.
        
        // Obtiene el tipo de reporte y el ID del módulo
        $reportType = $request->input('report_type');
        $moduloId = $request->input('report_type') === 'cultivo' ? $request->input('modulo_id') : $request->input('modulo_id_custom');

        // Validación inicial
        $request->validate([
            'report_type' => 'required|in:custom,cultivo',
        ]);

        // Validación condicional
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
            ];
        }

        // Prepara los datos para la plantilla del PDF
        $data = [
            'modulo' => $modulo,
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin' => $fecha_fin,
            'datos' => $datos,
        ];

        // Carga la vista del PDF y la convierte
        $pdf = Pdf::loadView('admin.reportes.module-report-pdf', $data);

        // Devuelve el PDF para que el navegador lo descargue
        return $pdf->download("reporte-modulo-{$modulo->codigo_identificador}.pdf");
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

