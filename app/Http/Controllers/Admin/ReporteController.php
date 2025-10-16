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
        return view('admin.reportes.module-form', compact('modulos'));
    }

    public function showModuleReportPreview(Request $request)
    {
        $request->validate([
            'modulo_id' => 'required|exists:modulos,id',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);

        $modulo = Modulo::with('vivero.user')->findOrFail($request->modulo_id);
        $periodo = CarbonPeriod::create($request->fecha_inicio, $request->fecha_fin);
        $datos = [];
        foreach ($periodo as $fecha) {
            $datos[] = [
                'fecha' => $fecha->format('d/m/Y'),
                'temperatura' => rand(180, 250) / 10,
                'ph' => rand(55, 75) / 10,
            ];
        }
        $fecha_inicio = $request->fecha_inicio;
        $fecha_fin = $request->fecha_fin;

        return view('admin.reportes.module-report', compact('modulo', 'datos', 'request', 'fecha_inicio', 'fecha_fin'));
    }

    public function generateModuleReport(Request $request)
    {
        $request->validate([ 'modulo_id' => 'required|exists:modulos,id', 'fecha_inicio' => 'required|date', 'fecha_fin' => 'required|date|after_or_equal:fecha_inicio' ]);
        $modulo = Modulo::with('vivero.user')->findOrFail($request->modulo_id);
        $periodo = CarbonPeriod::create($request->fecha_inicio, $request->fecha_fin);
        $datos = [];
        foreach ($periodo as $fecha) {
            $datos[] = [ 'fecha' => $fecha->format('d/m/Y'), 'temperatura' => rand(180, 250) / 10, 'ph' => rand(55, 75) / 10 ];
        }
        $data = [ 'modulo' => $modulo, 'fecha_inicio' => $request->fecha_inicio, 'fecha_fin' => $request->fecha_fin, 'datos' => $datos ];
        $pdf = Pdf::loadView('admin.reportes.module-report-pdf', $data);
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

