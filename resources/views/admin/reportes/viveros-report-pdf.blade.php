<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte General de Viveros</title>
    <style>
        @page { margin: 1cm; }
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            margin: 0; 
            color: #333;
            font-size: 9pt;
        }
        .header-logo {
            text-align: center;
            margin-bottom: 20px;
        }
        .header-logo img {
            height: 45px;
        }
        .report-title {
            font-size: 18pt;
            text-align: center;
            margin: 0;
            margin-bottom: 5px;
            color: #C2185B; /* Color de marca */
            font-weight: bold;
        }
        .report-subtitle {
            font-size: 10pt;
            text-align: center;
            margin-bottom: 20px;
            color: #555;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8pt;
        }
        .data-table th, .data-table td {
            border: 1px solid #ccc;
            padding: 5px;
            text-align: left;
        }
        .data-table thead th {
            background-color: #C2185B; /* Color de marca */
            color: #fff;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 7.5pt;
            text-align: center;
        }
        .data-table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .text-center {
            text-align: center;
        }
        .page-number:before {
            content: "Página " counter(page);
        }
    </style>
</head>
<body>
    <div class="header-logo">
        <img src="{{ public_path('images/Logo Hidrofrutilla 2.png') }}" alt="Logo">
    </div>

    <h1 class="report-title">Reporte General de Viveros</h1>
    <p class="report-subtitle">
        Fecha de Generación: {{ $fecha }}
        @if($filteredUser)
            <br>Filtrado por Usuario: <strong>{{ $filteredUser->full_name }}</strong>
        @endif
    </p>

    <table class="data-table">
        <thead>
            <tr>
                <th>Vivero</th>
                <th>Dueño</th>
                <th class="text-center">Total Módulos</th>
                <th class="text-center">Ocupados</th>
                <th class="text-center">Advertencias</th>
                <th class="text-center">Críticos</th>
                <th class="text-center">Offline</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($viveros as $vivero)
                <tr>
                    <td>{{ $vivero->nombre }}</td>
                    <td>{{ $vivero->user->full_name ?? 'N/A' }}</td>
                    <td class="text-center">{{ $vivero->modulos_total_count }}</td>
                    <td class="text-center">{{ $vivero->modulos_ocupados_count }}</td>
                    <td class="text-center">{{ $vivero->alertas_advertencia_count }}</td>
                    <td class="text-center">{{ $vivero->alertas_critico_count }}</td>
                    <td class="text-center">{{ $vivero->modulos_offline_count }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">No hay viveros para mostrar según el filtro seleccionado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>