<?php

namespace App\Http\Controllers;

use App\Models\Modulo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class DashboardController extends Controller
{
    // public function index()
    // {
    //     $user = Auth::user();

    //     if ($user->role->nombre_rol == 'Admin') {
    //         // --- LÓGICA PARA EL DASHBOARD DEL ADMIN ---
    //         $stats = [
    //             'total_users' => \App\Models\User::count(),
    //             'total_viveros' => \App\Models\Vivero::count(),
    //             'total_modulos' => \App\Models\Modulo::count(),
    //             'modulos_ocupados' => \App\Models\Modulo::where('estado', 'Ocupado')->count(),
    //         ];

    //         return view('dashboard', compact('stats'));

    //     } elseif ($user->role->nombre_rol == 'Dueño de Vivero') {
    //         // ... (la lógica del dueño que ya teníamos)
    //     }

    //     return redirect('/');
    // }
    public function index()
    {
        $user = Auth::user();
        $userRole = trim($user->role->nombre_rol);

        if ($userRole == 'Admin') {
            // Lógica para el dashboard del Admin
            $stats = [
                'total_users' => \App\Models\User::count(),
                'total_viveros' => \App\Models\Vivero::count(),
                'total_modulos' => \App\Models\Modulo::count(),
                'modulos_ocupados' => \App\Models\Modulo::where('estado', 'Ocupado')->count(),
            ];
            return view('dashboard', compact('stats'));

        } elseif ($userRole == 'Dueño de Vivero') {
            // Lógica para el dashboard del Dueño
            // Eager-load 'modulos' y 'latestLectura' para tener toda la data en la vista.
            $viveros = $user->viveros()->with(['modulos.latestLectura'])->withCount('modulos')->get(); 
            
            // Las estadísticas globales se mantienen
            $allModulos = $viveros->flatMap->modulos;
            $stats = [
                'total' => $allModulos->count(),
                'disponibles' => $allModulos->where('estado', 'Disponible')->count(),
                'ocupados' => $allModulos->where('estado', 'Ocupado')->count(),
                'mantenimiento' => $allModulos->where('estado', 'Mantenimiento')->count(),
            ];
            $cultivosActivos = $allModulos->where('estado', 'Ocupado')->sortBy('fecha_siembra');

            return view('owner.dashboard', compact('viveros', 'stats', 'cultivosActivos'));
        }

        return redirect('/');
    }

    public function toggleBomba(Modulo $modulo)
    {
        Gate::authorize('update', $modulo);
        $modulo->bomba_estado = !$modulo->bomba_estado;
        $modulo->save();

        return response()->json(['bomba_estado' => $modulo->bomba_estado]);
    }
}