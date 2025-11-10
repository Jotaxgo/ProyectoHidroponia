
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Módulo</title>
    <style>
        @page {
            margin: 1cm;
            /* Estilos para el pie de página */
            @bottom-center {
                content: "Página " counter(page) " de " counter(pages);
                font-family: 'Inter', sans-serif;
                font-size: 8pt;
                color: #555555;
            }
        }
        body { 
            font-family: 'Inter', sans-serif; 
            margin: 0; 
            color: #1a1a1a; /* text-dark */
            font-size: 10pt;
        }
        .container {
            padding: 1cm;
        }
        .header { 
            text-align: center; 
            margin-bottom: 1.5cm; 
            border-bottom: 1px solid #e0e0e0; /* border */
            padding-bottom: 10px;
        }
        .header img { 
            height: 50px; 
            margin-bottom: 10px; 
        }
        .header h1 { 
            font-size: 20pt; 
            color: #9c0000; /* strawberry-dark */
            margin: 0; 
            font-weight: bold;
        }
        .info-section {
            background-color: #f8f8f8; /* bg-gray-50 */
            border: 1px solid #e0e0e0; /* border */
            padding: 15px;
            margin-bottom: 1.5cm;
            border-radius: 8px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }
        .info-item {
            width: 48%; /* Para dos columnas */
            margin-bottom: 10px;
        }
        .info-item strong {
            color: #555555; /* text-muted */
            font-size: 8pt;
            display: block;
            margin-bottom: 2px;
        }
        .info-item span {
            color: #1a1a1a; /* text-dark */
            font-size: 10pt;
            font-weight: bold;
        }
        .info-item span.highlight {
            color: #ff4b65; /* strawberry */
        }
        .table-section {
            margin-top: 1cm;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 10px; 
            font-size: 8pt;
        }
        th, td { 
            border: 1px solid #e0e0e0; /* border */
            padding: 6px 4px; 
            text-align: center; 
        }
        thead th { 
            background-color: #f5f5f5; /* bg-gray-100 */
            color: #1a1a1a; /* text-dark */
            font-weight: bold; 
            text-transform: uppercase;
        }
        tbody tr:nth-child(even) { 
            background-color: #fafafa; /* bg-gray-50 */
        }
        .no-data { 
            text-align: center; 
            color: #555555; 
            padding: 20px; 
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ public_path('images/Logo Hidrofrutilla 2.png') }}" alt="Logo Hidrofrutilla">
            <h1>Reporte de Módulo Hidropónico</h1>
        </div>

        <div class="info-section">
            <div class="info-item">
                <strong>Módulo:</strong> 
                <span class="highlight">{{ $modulo->codigo_identificador }} ({{ $modulo->vivero->nombre }})</span>
            </div>
            <div class="info-item">
                <strong>Periodo:</strong> 
                <span>{{ $fechaInicio }} al {{ $fechaFin }}</span>
            </div>
            <div class="info-item">
                <strong>Dueño:</strong> 
                <span>{{ $dueno->full_name }}</span>
            </div>
            <div class="info-item">
                <strong>Generado por:</strong> 
                <span>{{ $generadoPor }} ({{ $fechaGeneracion }})</span>
            </div>
        </div>

        <div class="table-section">
            @include('admin.reportes.partials.module-report-table', ['isPdf' => true])
        </div>
    </div>
</body>
</html>