<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Vivero;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class GenerateViverosReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reports:generate-viveros';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Genera un informe de resumen mensual para cada usuario "Dueño de Vivero" y lo guarda en el disco.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando la generación de informes de viveros...');

        // 1. Obtener todos los usuarios que son "Dueño de Vivero"
        $owners = User::whereHas('role', function ($query) {
            $query->where('nombre_rol', 'Dueño de Vivero');
        })->get();

        if ($owners->isEmpty()) {
            $this->info('No se encontraron usuarios "Dueño de Vivero".');
            return 0;
        }

        foreach ($owners as $owner) {
            $this->info("Generando informe para: {$owner->full_name} (ID: {$owner->id})");

            // 2. Lógica de cálculo (adaptada de ReporteController)
            $viveros = Vivero::where('user_id', $owner->id)->with('modulos.latestLectura')->get();

            if ($viveros->isEmpty()) {
                $this->line("El usuario {$owner->full_name} no tiene viveros. Saltando.");
                continue;
            }

            // Lógica de cálculo de alertas
            $PH_MIN = 5.8;
            $PH_MAX = 6.4;
            $TEMP_MAX = 25.0;
            $OFFLINE_THRESHOLD_MINUTES = 10;

            foreach ($viveros as $vivero) {
                $modulosOcupados = 0;
                $alertasAdvertencia = 0;
                $alertasCritico = 0;
                $modulosOffline = 0;

                foreach ($vivero->modulos as $modulo) {
                    if ($modulo->estado === 'Ocupado') {
                        $modulosOcupados++;
                    }
                    $latestReading = $modulo->latestLectura;
                    if ($latestReading) {
                        $diffInMinutes = now()->diffInMinutes($latestReading->created_at);
                        if ($diffInMinutes > $OFFLINE_THRESHOLD_MINUTES) {
                            $modulosOffline++;
                            continue;
                        }
                        $phVal = (float)$latestReading->ph;
                        $tempVal = (float)$latestReading->temperatura;
                        if ($phVal < ($PH_MIN - 0.5) || $phVal > ($PH_MAX + 0.5)) {
                            $alertasCritico++;
                        } elseif ($phVal < $PH_MIN || $phVal > $PH_MAX || $tempVal > $TEMP_MAX) {
                            $alertasAdvertencia++;
                        }
                    } else {
                        if ($modulo->estado !== 'Disponible') {
                            $modulosOffline++;
                        }
                    }
                }
                $vivero->modulos_total_count = $vivero->modulos->count();
                $vivero->modulos_ocupados_count = $modulosOcupados;
                $vivero->alertas_advertencia_count = $alertasAdvertencia;
                $vivero->alertas_critico_count = $alertasCritico;
                $vivero->modulos_offline_count = $modulosOffline;
            }

            // 3. Preparar datos para el PDF
            $data = [
                'viveros' => $viveros,
                'fecha' => Carbon::now()->isoFormat('D MMMM YYYY'),
                'filteredUser' => $owner
            ];

            // 4. Generar y guardar el PDF
            $pdf = Pdf::loadView('admin.reportes.viveros-report-pdf', $data);
            
            // Crear un nombre de archivo único
            $date = Carbon::now()->format('Y-m');
            $fileName = "reporte-viveros-{$owner->id}-{$date}.pdf";
            
            // Guardar en storage/app/reports/viveros/{userId}/
            $directory = "reports/viveros/{$owner->id}";
            Storage::makeDirectory($directory);
            $path = "{$directory}/{$fileName}";
            Storage::put($path, $pdf->output());

            $this->info("Informe guardado en: storage/app/{$path}");
        }

        $this->info('¡Generación de informes completada!');
        return 0;
    }
}
