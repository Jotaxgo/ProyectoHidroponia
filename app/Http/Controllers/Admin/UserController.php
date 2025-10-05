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
        // 1. Obtenemos todos los usuarios de la base de datos
        $users = User::with('role')->get();

        // 2. Devolvemos una vista y le pasamos la variable 'users'
        // Crearemos esta vista en el siguiente paso
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

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => null,
            'role_id' => $request->role_id,
        ]);

        // --- LÓGICA PARA ENVIAR CORREO ---
        // 1. Generamos un token para el reseteo de contraseña.
        /** @var \Illuminate\Contracts\Auth\PasswordBroker $passwordBroker */
        $passwordBroker = Password::broker();
        $token = $passwordBroker->createToken($user);

        // AÑADE ESTA LÍNEA DE PRUEBA AQUÍ
        // dd('PRUEBA: A punto de enviar correo');

        // 2. Enviamos la notificación al usuario con el token.
        $user->notify(new SendUserInvitation($token));
        // --- FIN DE LA LÓGICA ---

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
        $user->forceDelete(); // <-- Esto es un borrado físico y permanente

        return redirect()->route('admin.users.trash')->with('success', 'Usuario eliminado permanentemente.');
    }
}
