<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SensorLimit;
use App\Models\Vivero;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class ViveroSettingsController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Vivero  $vivero
     * @return \Illuminate\View\View
     */
    public function edit(Vivero $vivero)
    {
        // Policy check to ensure the user can update the vivero
        Gate::authorize('update', $vivero);

        $defaultLimits = config('hydroponics.limits', []);
        $customLimits = SensorLimit::where('vivero_id', $vivero->id)->get()->keyBy('sensor');
        
        // Define the master list of all sensors that the application uses
        $all_sensors = ['ph', 'ec', 'temperatura', 'humedad', 'luz'];
        $settings = [];

        foreach ($all_sensors as $sensor) {
            // Priority order: Custom DB limit > Default Config limit > Null
            if (isset($customLimits[$sensor])) {
                $settings[$sensor] = [
                    'min' => $customLimits[$sensor]->min,
                    'max' => $customLimits[$sensor]->max,
                ];
            } elseif (isset($defaultLimits[$sensor])) {
                $settings[$sensor] = [
                    'min' => $defaultLimits[$sensor]['min'] ?? null,
                    'max' => $defaultLimits[$sensor]['max'] ?? null,
                ];
            } else {
                // Ensure the key exists to prevent errors in the view, even if no limits are set anywhere
                $settings[$sensor] = [
                    'min' => null,
                    'max' => null,
                ];
            }
        }
        
        return view('admin.viveros.settings', compact('vivero', 'settings'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vivero  $vivero
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Vivero $vivero)
    {
        Gate::authorize('update', $vivero);

        $sensors = ['ph', 'ec', 'temperatura', 'luz', 'humedad'];
        $rules = [];
        
        // Build validation rules for all sensor fields
        foreach ($sensors as $sensor) {
            $rules["{$sensor}_min"] = 'required|numeric';
            $rules["{$sensor}_max"] = 'required|numeric|gte:'.$sensor.'_min';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        // Iterate and update or create each sensor limit
        foreach ($sensors as $sensor) {
            SensorLimit::updateOrCreate(
                ['vivero_id' => $vivero->id, 'sensor' => $sensor],
                ['min' => $request->input("{$sensor}_min"), 'max' => $request->input("{$sensor}_max")]
            );
        }

        return redirect()->route('admin.viveros.settings.edit', $vivero)
                         ->with('success', 'Ajustes de sensores guardados correctamente.');
    }
}