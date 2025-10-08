<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role->nombre_rol == 'Admin') {
            // Si es Admin, muestra el dashboard de admin.
            return view('dashboard');

        } elseif ($user->role->nombre_rol == 'Dueño de Vivero') {
            // Si es Dueño, busca sus viveros.
            $viveros = $user->viveros()->withCount('modulos')->get();

            // Y lo envía a una vista nueva y personalizada.
            return view('owner.dashboard', compact('viveros'));
        }

        // Si es otro tipo de usuario, lo redirige a la página principal.
        return redirect('/');
    }
}