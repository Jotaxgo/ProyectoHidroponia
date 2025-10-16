<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte General de Viveros</title>
    <style>
        body { font-family: sans-serif; margin: 20px; color: #333; }
        h1, h2 { color: #073B3A; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 12px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        thead { background-color: #0B6E4F; color: white; }
        .header { margin-bottom: 30px; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h1>HIDROFRUTILLA</h1>
        <h2>Reporte General de Viveros</h2>
        <p>Fecha de Generación: {{ $fecha }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Vivero</th>
                <th>Dueño</th>
                <th class="text-center">Módulos Totales</th>
                <th class="text-center">Disponibles</th>
                <th class="text-center">Ocupados</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($viveros as $vivero)
                <tr>
                    <td>{{ $vivero->nombre }}</td>
                    <td>{{ $vivero->user->full_name ?? 'N/A' }}</td>
                    <td class="text-center">{{ $vivero->modulos_count }}</td>
                    <td class="text-center">{{ $vivero->modulos_disponibles }}</td>
                    <td class="text-center">{{ $vivero->modulos_ocupados }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No hay viveros registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>