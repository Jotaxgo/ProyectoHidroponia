<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vivero;
use App\Models\Modulo;
use Illuminate\Validation\Rule;

class ModuloController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Vivero $vivero)
    {
        // Obtenemos solo los módulos que pertenecen a ESE vivero
        $modulos = $vivero->modulos()->get();

        return view('admin.modulos.index', compact('vivero', 'modulos'));
    }

    /**
     * Muestra el formulario para crear un nuevo módulo en un vivero específico.
     */
    public function create(Vivero $vivero)
    {
        return view('admin.modulos.create', compact('vivero'));
    }

    /**
     * Guarda el nuevo módulo en la base de datos.
     */
    public function store(Request $request, Vivero $vivero)
    {
        $request->validate([
            'codigo_identificador' => 'required|string|max:255|unique:modulos',
            'tipo_sistema' => 'required|string|max:255',
            'capacidad' => 'required|integer|min:1',
        ]);

        $vivero->modulos()->create([
            'codigo_identificador' => $request->codigo_identificador,
            'tipo_sistema' => $request->tipo_sistema,
            'capacidad' => $request->capacidad,
            // El estado por defecto es 'Disponible', así que no necesitamos pasarlo
        ]);

        return redirect()->route('admin.viveros.modulos.index', $vivero)
                         ->with('success', 'Módulo creado exitosamente.');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Muestra el formulario para editar un módulo existente.
     */
    public function edit(Vivero $vivero, Modulo $modulo)
    {
        return view('admin.modulos.edit', compact('vivero', 'modulo'));
    }

    /**
     * Actualiza el módulo en la base de datos.
     */
    public function update(Request $request, Vivero $vivero, Modulo $modulo)
    {
        $request->validate([
            'codigo_identificador' => ['required', 'string', 'max:255', Rule::unique('modulos')->ignore($modulo->id)],
            'tipo_sistema' => 'required|string|max:255',
            'capacidad' => 'required|integer|min:1',
            'estado' => 'required|string', // Añadimos el estado
        ]);

        $modulo->update($request->all());

        return redirect()->route('admin.viveros.modulos.index', $vivero)
                         ->with('success', 'Módulo actualizado exitosamente.');
    }

    /**
     * Elimina (lógicamente) el módulo de la base de datos.
     */
    public function destroy(Vivero $vivero, Modulo $modulo)
    {
        $modulo->delete();

        return redirect()->route('admin.viveros.modulos.index', $vivero)
                        ->with('success', 'Módulo eliminado exitosamente.');
    }

    /**
     * Muestra una lista de los módulos eliminados lógicamente para un vivero.
     */
    public function trash(Vivero $vivero)
    {
        $trashedModulos = $vivero->modulos()->onlyTrashed()->get();
        return view('admin.modulos.trash', compact('vivero', 'trashedModulos'));
    }

    /**
     * Restaura un módulo eliminado lógicamente.
     */
    public function restore(Vivero $vivero, $moduloId)
    {
        $modulo = Modulo::withTrashed()->where('vivero_id', $vivero->id)->findOrFail($moduloId);
        $modulo->restore();

        return redirect()->route('admin.viveros.modulos.trash', $vivero)->with('success', 'Módulo restaurado exitosamente.');
    }

    /**
     * Elimina permanentemente un módulo de la base de datos.
     */
    public function forceDelete(Vivero $vivero, $moduloId)
    {
        $modulo = Modulo::withTrashed()->where('vivero_id', $vivero->id)->findOrFail($moduloId);
        $modulo->forceDelete();

        return redirect()->route('admin.viveros.modulos.trash', $vivero)->with('success', 'Módulo eliminado permanentemente.');
    }
}
