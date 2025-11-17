<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Módulo</title>
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
            margin-bottom: 20px;
            color: #C2185B; /* Tono de rojo más profesional */
            font-weight: bold;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            border: 1px solid #ddd;
        }
        .info-table td {
            width: 50%;
            vertical-align: top;
            padding: 10px;
        }
        .info-table .section-title {
            font-size: 11pt;
            font-weight: bold;
            color: #C2185B;
            margin-bottom: 8px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 4px;
        }
        .info-table .info-item {
            margin-bottom: 5px;
            font-size: 9pt;
        }
        .info-table .info-item strong {
            font-weight: bold;
            color: #555;
        }
        .parameter-box {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
        }
        .parameter-table {
            width: 100%;
            font-size: 8pt;
        }
        .parameter-table td {
            padding: 3px 5px;
            border-bottom: 1px solid #eee;
        }
        .parameter-table td:first-child {
            font-weight: bold;
            color: #555;
        }
        .main-data-title {
            font-size: 14pt;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
            text-align: center;
        }
        /* Estilos para la tabla de datos principal (inyectados desde el parcial) */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8pt;
        }
        .data-table th, .data-table td {
            border: 1px solid #ccc;
            padding: 4px;
            text-align: center;
        }
        .data-table thead th {
            background-color: #333;
            color: #fff;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 7pt;
        }
        .data-table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .data-table .prom-cell {
            background-color: #e8e8e8;
            font-weight: bold;
        }
        .no-data {
            text-align: center;
            padding: 20px;
            font-style: italic;
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

    <h1 class="report-title">Reporte de Módulo Hidropónico</h1>

    <table class="info-table">
        <tr>
            <td>
                <div class="section-title">Información del Módulo</div>
                <div class="info-item">
                    <strong>Módulo:</strong> {{ $modulo->codigo_identificador }} ({{ $modulo->vivero->nombre }})
                </div>
                <div class="info-item">
                    <strong>Cultivo:</strong> {{ $cultivoActual ?? 'N/A' }}
                </div>
                <div class="info-item">
                    <strong>Siembra:</strong> {{ $fechaSiembra ? \Carbon\Carbon::parse($fechaSiembra)->isoFormat('D MMM YYYY') : 'N/A' }}
                </div>
                <br>
                <div class="section-title">Información del Reporte</div>
                <div class="info-item">
                    <strong>Periodo:</strong> {{ $fechaInicio }} al {{ $fechaFin }}
                </div>
                <div class="info-item">
                    <strong>Generado por:</strong> {{ $generadoPor }}
                </div>
                 <div class="info-item">
                    <strong>Fecha:</strong> {{ $fechaGeneracion }}
                </div>
            </td>
            <td>
                <div class="parameter-box">
                    <div class="section-title">Parámetros del Cultivo</div>
                    <table class="parameter-table">
                        @php
                            $units = ['ec' => 'mS/cm', 'temperatura' => '°C', 'humedad' => '%'];
                        @endphp
                        @foreach(['ph', 'ec', 'temperatura', 'humedad'] as $param)
                            @if(isset($limits[$param]['min']) && isset($limits[$param]['max']))
                                <tr>
                                    <td>{{ strtoupper($param) }}</td>
                                    <td>{{ $limits[$param]['min'] }} - {{ $limits[$param]['max'] }} {{ $units[$param] ?? '' }}</td>
                                </tr>
                            @endif
                        @endforeach
                    </table>
                </div>
            </td>
        </tr>
    </table>

    <h2 class="main-data-title">Registro de Datos por Hora</h2>

    @include('admin.reportes.partials.module-report-table', ['isPdf' => true])

</body>
</html>