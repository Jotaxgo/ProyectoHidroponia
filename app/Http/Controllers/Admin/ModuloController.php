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
            'capacidad' => 'required|integer|min:1',
            'device_id' => 'nullable|string|max:255',
            'ph_min' => 'nullable|numeric',
            'ph_max' => 'nullable|numeric|gte:ph_min',
            'temp_min' => 'nullable|numeric',
            'temp_max' => 'nullable|numeric|gte:temp_min',
        ]);

        // Preparamos el array para la columna JSON
        $hardwareInfo = [
            'device_id' => $request->device_id,
            'ph_min' => $request->ph_min,
            'ph_max' => $request->ph_max,
            'temp_min' => $request->temp_min,
            'temp_max' => $request->temp_max,
        ];

        $vivero->modulos()->create([
            'codigo_identificador' => $request->codigo_identificador,
            'tipo_sistema' => $request->tipo_sistema,
            'capacidad' => $request->capacidad,
            'hardware_info' => $hardwareInfo, // Guardamos el array completo
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
            'estado' => 'required|string',
            'device_id' => 'nullable|string|max:255',
            // --- NUEVAS VALIDACIONES ---
            'ph_min' => 'nullable|numeric',
            'ph_max' => 'nullable|numeric|gte:ph_min', // gte: greater than or equal
            'temp_min' => 'nullable|numeric',
            'temp_max' => 'nullable|numeric|gte:temp_min',
        ]);

        // Obtenemos la info de hardware existente o creamos un array vacío
        $hardwareInfo = $modulo->hardware_info ?? [];

        // Actualizamos los valores con los nuevos datos del formulario
        $hardwareInfo['device_id'] = $request->device_id;
        $hardwareInfo['ph_min'] = $request->ph_min;
        $hardwareInfo['ph_max'] = $request->ph_max;
        $hardwareInfo['temp_min'] = $request->temp_min;
        $hardwareInfo['temp_max'] = $request->temp_max;

        $modulo->update([
            'codigo_identificador' => $request->codigo_identificador,
            'tipo_sistema' => $request->tipo_sistema,
            'capacidad' => $request->capacidad,
            'estado' => $request->estado,
            'hardware_info' => $hardwareInfo, // Guardamos el array completo
        ]);

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

    /**
     * Muestra una lista de TODOS los módulos del sistema, con filtros.
     */
    public function indexAll(Request $request)
    {
        // 1. Obtenemos los datos para los filtros
        $viveros = Vivero::all();

        // 2. Empezamos la consulta
        $modulosQuery = Modulo::with('vivero');

        // 3. Aplicamos los filtros si existen
        if ($request->filled('estado')) {
            $modulosQuery->where('estado', $request->estado);
        }
        if ($request->filled('vivero_id')) {
            $modulosQuery->where('vivero_id', $request->vivero_id);
        }

        $modulos = $modulosQuery->get();

        // 4. Pasamos todo a la vista
        return view('admin.modulos.index_all', compact('modulos', 'viveros'));
    }
}
