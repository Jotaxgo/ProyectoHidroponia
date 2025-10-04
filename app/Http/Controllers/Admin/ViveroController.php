<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vivero;
use Illuminate\Validation\Rule; // <-- Importante para la validación

class ViveroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $viveros = Vivero::all();
        return view('admin.viveros.index', compact('viveros'));
    }

    /**
     * Muestra el formulario para crear un nuevo vivero.
     */
    public function create()
    {
        return view('admin.viveros.create');
    }

    /**
     * Guarda el nuevo vivero en la base de datos.
     */
    public function store(Request $request)
    {
        // 1. Validación de los datos del formulario
        $request->validate([
            'nombre' => 'required|string|max:255|unique:viveros',
            'ubicacion' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        // 2. Creación del vivero
        Vivero::create([
            'nombre' => $request->nombre,
            'ubicacion' => $request->ubicacion,
            'descripcion' => $request->descripcion,
        ]);

        // 3. Redirección con mensaje de éxito
        return redirect()->route('admin.viveros.index')->with('success', 'Vivero creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Muestra el formulario para editar un vivero existente.
     */
    public function edit(Vivero $vivero)
    {
        return view('admin.viveros.edit', compact('vivero'));
    }

     /**
     * Actualiza el vivero en la base de datos.
     */
    public function update(Request $request, Vivero $vivero)
    {
        $request->validate([
            'nombre' => ['required', 'string', 'max:255', Rule::unique('viveros')->ignore($vivero->id)],
            'ubicacion' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        $vivero->update($request->all());

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
}
