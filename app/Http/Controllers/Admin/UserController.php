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


class UserController extends Controller
{
    /**
     * Muestra una lista de todos los usuarios.
     */
    public function index()
    {
        $users = User::with('role', 'viveros')->get(); // Añadimos 'viveros'
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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role_id' => 'required|exists:roles,id',
        ]);

        $role = Role::find($request->role_id);

        // Validación condicional para el vivero
        if ($role && $role->nombre_rol == 'Dueño de Vivero') {
            $request->validate([
                'vivero_nombre' => 'required|string|max:255|unique:viveros,nombre',
                'vivero_ubicacion' => 'required|string|max:255',
            ]);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => null,
            'role_id' => $request->role_id,
        ]);

        // Si es Dueño, creamos el vivero y lo asociamos
        if ($role && $role->nombre_rol == 'Dueño de Vivero') {
            $user->viveros()->create([
                'nombre' => $request->vivero_nombre,
                'ubicacion' => $request->vivero_ubicacion,
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

        // Contamos cuántos viveros le pertenecen a este usuario.
        if ($user->viveros()->count() > 0) {
            // Obtenemos los nombres de los viveros
            $viveroNames = $user->viveros->pluck('nombre')->join(', ');

            // Creamos un mensaje de error más específico
            $errorMessage = "Este usuario no puede ser eliminado porque es dueño del/los vivero(s): {$viveroNames}. Por favor, reasigna estos viveros a otro dueño primero.";

            return redirect()->route('admin.users.trash')->with('error', $errorMessage);
        }

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
