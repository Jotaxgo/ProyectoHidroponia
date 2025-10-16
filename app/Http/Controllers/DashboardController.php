<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            $viveros = $user->viveros()->withCount('modulos')->get(); // Obtenemos los viveros
            $viveroIds = $viveros->pluck('id');
            $modulos = \App\Models\Modulo::whereIn('vivero_id', $viveroIds)->get();

            $stats = [
                'total' => $modulos->count(),
                'disponibles' => $modulos->where('estado', 'Disponible')->count(),
                'ocupados' => $modulos->where('estado', 'Ocupado')->count(),
                'mantenimiento' => $modulos->where('estado', 'Mantenimiento')->count(),
            ];
            $cultivosActivos = $modulos->where('estado', 'Ocupado')->sortBy('fecha_siembra');

            // --- CORRECCIÓN AQUÍ ---
            // Ahora sí pasamos la variable $viveros a la vista
            return view('owner.dashboard', compact('viveros', 'stats', 'cultivosActivos'));
        }

        return redirect('/');
    }
}