<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role; // <-- Importa el modelo Role
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Password; // <-- AÑADE ESTO
use App\Notifications\SendUserInvitation;  // <-- AÑADE ESTO
use App\Models\Vivero; 


class UserController extends Controller
{
    /**
     * Muestra una lista de todos los usuarios.
     */
    public function index()
    {
        $users = User::with('role')->withCount('viveros')->get();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Muestra el formulario para crear un nuevo usuario.
     */
    // public셔 
    public function create()
    {
        $roles = Role::all(); // Obtenemos todos los roles para pasarlos al formulario
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombres' => 'required|string|max:255',
            'primer_apellido' => 'required|string|max:255',
            'segundo_apellido' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role_id' => 'required|exists:roles,id',
            // ... validación condicional del vivero
        ]);

        $role = Role::find($request->role_id);

        // Validación condicional para el vivero
        if ($role && $role->nombre_rol == 'Dueño de Vivero') {
            $request->validate([
                'vivero_nombre' => 'required|string|max:255|unique:viveros,nombre',
                'latitud' => 'required|numeric',
                'longitud' => 'required|numeric',
            ]);
        }

        $user = User::create([
            'nombres' => $request->nombres,
            'primer_apellido' => $request->primer_apellido,
            'segundo_apellido' => $request->segundo_apellido,
            'email' => $request->email,
            'password' => null,
            'role_id' => $request->role_id,
        ]);

        // Si es Dueño, creamos el vivero y lo asociamos
        if ($role && $role->nombre_rol == 'Dueño de Vivero') {
            $user->viveros()->create([
                'nombre' => $request->vivero_nombre,
                'latitud' => $request->latitud,
                'longitud' => $request->longitud,
                'descripcion' => $request->vivero_descripcion,
            ]);
        }

        // --- INICIO: LÓGICA DE INVITACIÓN (ESTO ES LO QUE FALTABA) ---
        // 1. Generamos un token para el reseteo de contraseña.
        $token = Password::broker()->createToken($user);

        // 2. Enviamos la notificación al usuario con el token.
        $user->notify(new SendUserInvitation($token));
        // --- FIN: LÓGICA DE INVITACIÓN ---

        return redirect()->route('admin.users.index')->with('success', 'Usuario creado. Se ha enviado una invitación por correo.');
    }

    /**
     * Muestra el formulario para editar un usuario existente.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Actualiza un usuario en la base de datos.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role_id' => 'required|exists:roles,id',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = $request->only('name', 'email', 'role_id');
        
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Elimina (lógicamente) un usuario de la base de datos.
     */
    public function destroy(User $user)
    {
        $user->delete(); // Gracias al trait, esto hace un Soft Delete

        return redirect()->route('admin.users.index')->with('success', 'Usuario eliminado exitosamente.');
    }

    /**
     * Muestra una lista de los usuarios eliminados lógicamente.
     */
    public function trash()
    {
        $trashedUsers = User::onlyTrashed()->get(); // <-- Obtiene SÓLO los eliminados
        return view('admin.users.trash', compact('trashedUsers'));
    }

    /**
     * Restaura un usuario eliminado lógicamente.
     */
    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id); // <-- Busca incluyendo eliminados
        $user->restore();

        return redirect()->route('admin.users.trash')->with('success', 'Usuario restaurado exitosamente.');
    }

    /**
     * Elimina permanentemente un usuario de la base de datos.
     */
    public function forceDelete($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $viveros = $user->viveros; // Obtenemos la colección de viveros del usuario

        // CASO 1: Si el usuario tiene exactamente UN vivero
        if ($viveros->count() === 1) {
            $viveroAReasignar = $viveros->first();

            // AÑADIMOS UNA "NOTA" A LA SESIÓN PARA SABER A DÓNDE VOLVER
            session()->put('redirect_after_update', route('admin.users.trash'));
            
            // Redirigimos a la página para editar ese vivero con un mensaje
            return redirect()->route('admin.viveros.edit', $viveroAReasignar)
                            ->with('info', "Para poder eliminar a {$user->full_name}, primero debes reasignar este vivero a otro dueño.");

        // CASO 2: Si el usuario tiene MÁS DE UN vivero
        } elseif ($viveros->count() > 1) {
            $viveroNames = $viveros->pluck('nombre')->join(', ');
            $errorMessage = "Este usuario no puede ser eliminado porque es dueño de los siguientes viveros: {$viveroNames}. Por favor, reasígnalos a otro dueño primero.";
            // Redirigimos de vuelta a la papelera con el error
            return redirect()->back()->with('error', $errorMessage);
        }

        // CASO 3: Si no tiene viveros, se puede borrar
        $user->forceDelete();

        return redirect()->route('admin.users.trash')->with('success', 'Usuario eliminado permanentemente.');
    }

    /**
     * Muestra una lista de los viveros que le pertenecen a un usuario específico.
     */
    public function showViveros(User $user)
    {
        // Usamos la relación 'viveros' que ya definimos en el modelo User
        $viveros = $user->viveros;

        return view('admin.users.viveros', compact('user', 'viveros'));
    }
}
