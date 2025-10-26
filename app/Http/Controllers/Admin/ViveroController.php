<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vivero;
use Illuminate\Validation\Rule; // <-- Importante para la validación
use App\Models\User;
use App\Models\Role;

class ViveroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Obtenemos la lista de todos los posibles dueños para el filtro
        $ownerRole = Role::where('nombre_rol', 'Dueño de Vivero')->first();
        $dueños = User::where('role_id', $ownerRole->id)->get();

        // --- LA CORRECCIÓN ESTÁ AQUÍ ---
        // Empezamos la consulta de viveros, siempre cargando la relación con el dueño
        $viverosQuery = Vivero::with('user')->withCount('modulos');

        // Si en la URL viene un filtro de dueño, lo aplicamos
        if ($request->filled('dueño_id')) {
            $viverosQuery->where('user_id', $request->dueño_id);
        }

        $viveros = $viverosQuery->get();
        

        return view('admin.viveros.index', compact('viveros', 'dueños'));
    }

    /**
     * Muestra el formulario para crear un nuevo vivero.
     */
    public function create()
    {
        // Buscamos el ID del rol "Dueño de Vivero"
        $ownerRoleId = Role::where('nombre_rol', 'Dueño de Vivero')->first()->id;

        // Obtenemos todos los usuarios que tienen ese rol
        $dueños = User::where('role_id', $ownerRoleId)->get();

        return view('admin.viveros.create', compact('dueños'));
    }

    /**
     * Guarda el nuevo vivero en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:viveros',
            'user_id' => 'required|exists:users,id', // <-- VALIDACIÓN DEL DUEÑO
            'latitud' => 'required|numeric',
            'longitud' => 'required|numeric',
            'descripcion' => 'nullable|string',
        ]);

        Vivero::create($request->all());

        return redirect()->route('admin.viveros.index')->with('success', 'Vivero creado y asignado exitosamente.');
    }

    /**
     * Muestra los detalles de un vivero específico.
     */
    public function show(Vivero $vivero)
    {
        return view('admin.viveros.show', compact('vivero'));
    }

    /**
     * Muestra el formulario para editar un vivero existente.
     */
    public function edit(Vivero $vivero)
    {
        // Buscamos el ID del rol "Dueño de Vivero"
        $ownerRoleId = Role::where('nombre_rol', 'Dueño de Vivero')->first()->id;

        // Obtenemos todos los usuarios que tienen ese rol
        $dueños = User::where('role_id', $ownerRoleId)->get();

        return view('admin.viveros.edit', compact('vivero', 'dueños'));
    }

     /**
     * Actualiza el vivero en la base de datos.
     */
    public function update(Request $request, Vivero $vivero)
    {
        $request->validate([
            'nombre' => ['required', 'string', 'max:255', Rule::unique('viveros')->ignore($vivero->id)],
            'user_id' => 'required|exists:users,id',
            'latitud' => 'required|numeric',
            'longitud' => 'required|numeric',
            'descripcion' => 'nullable|string',
        ]);

        $vivero->update($request->all());

        // --- LÓGICA DE REDIRECCIÓN INTELIGENTE ---
        // 1. Revisa si existe la "nota" en la sesión.
        if (session()->has('redirect_after_update')) {
            // 2. Obtenemos la URL guardada.
            $redirectUrl = session('redirect_after_update');

            // 3. Eliminamos la "nota" para que no afecte a futuras actualizaciones.
            session()->forget('redirect_after_update');

            // 4. Redirigimos a la URL guardada (la papelera de usuarios).
            return redirect($redirectUrl)->with('success', 'Vivero reasignado. Ahora puedes eliminar al usuario original.');
        }
        // --- FIN DE LA LÓGICA ---

        // Si no hay "nota", hacemos la redirección normal.
        return redirect()->route('admin.viveros.index')->with('success', 'Vivero actualizado exitosamente.');
    }

    /**
     * Elimina (lógicamente) el vivero de la base de datos.
     */
    public function destroy(Vivero $vivero)
    {
        $vivero->delete(); // Gracias al trait SoftDeletes, esto es un borrado lógico

        return redirect()->route('admin.viveros.index')->with('success', 'Vivero eliminado exitosamente.');
    }

    /**
     * Muestra una lista de los viveros eliminados lógicamente.
     */
    public function trash()
    {
        $trashedViveros = Vivero::onlyTrashed()->get();
        return view('admin.viveros.trash', compact('trashedViveros'));
    }

    /**
     * Restaura un vivero eliminado lógicamente.
     */
    public function restore($id)
    {
        $vivero = Vivero::withTrashed()->findOrFail($id);
        $vivero->restore();

        return redirect()->route('admin.viveros.trash')->with('success', 'Vivero restaurado exitosamente.');
    }

    /**
     * Elimina permanentemente un vivero de la base de datos.
     */
    public function forceDelete($id)
    {
        $vivero = Vivero::withTrashed()->findOrFail($id);
        $vivero->forceDelete();

        return redirect()->route('admin.viveros.trash')->with('success', 'Vivero eliminado permanentemente.');
    }
}
