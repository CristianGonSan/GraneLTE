<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ $title ?? 'Informe de Inventario' }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @page {
            margin: 15mm 12mm 15mm 12mm;
        }

        html,
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10pt;
            color: #1a1a1a;
            background: #ffffff;
            line-height: 1.4;
        }

        /* Tipografia */
        .text-xs {
            font-size: 7pt;
        }

        .text-sm {
            font-size: 8.5pt;
        }

        .text-base {
            font-size: 10pt;
        }

        .text-lg {
            font-size: 12pt;
        }

        .text-xl {
            font-size: 14pt;
        }

        .font-bold {
            font-weight: bold;
        }

        .font-normal {
            font-weight: normal;
        }

        .text-left {
            text-align: left;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .uppercase {
            text-transform: uppercase;
        }

        .tracking-wide {
            letter-spacing: 0.05em;
        }

        .text-dark {
            color: #1a1a1a;
        }

        .text-muted {
            color: #6b7280;
        }

        .text-white {
            color: #ffffff;
        }

        .text-success {
            color: #15803d;
        }

        .text-danger {
            color: #b91c1c;
        }

        /* Espaciado */
        .mt-1 {
            margin-top: 4pt;
        }

        .mt-2 {
            margin-top: 8pt;
        }

        .mt-3 {
            margin-top: 12pt;
        }

        .mb-1 {
            margin-bottom: 4pt;
        }

        .mb-2 {
            margin-bottom: 8pt;
        }

        .mb-3 {
            margin-bottom: 12pt;
        }

        .p-1 {
            padding: 4pt;
        }

        .p-2 {
            padding: 8pt;
        }

        .pb-1 {
            padding-bottom: 4pt;
        }

        .pt-1 {
            padding-top: 4pt;
        }

        /* Bordes */
        .border {
            border: 1pt solid #d1d5db;
        }

        .border-top {
            border-top: 1pt solid #d1d5db;
        }

        .border-bottom {
            border-bottom: 1pt solid #d1d5db;
        }

        .rounded {
            border-radius: 3pt;
        }

        /* Saltos de pagina */
        .page-break-before {
            page-break-before: always;
        }

        .avoid-break {
            page-break-inside: avoid;
        }

        /* Tablas */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8pt;
        }

        th,
        td {
            padding: 3pt 4pt;
            vertical-align: top;
        }

        thead tr {
            background-color: #1f2937;
            color: #ffffff;
        }

        tbody tr:nth-child(even) {
            background-color: #f3f4f6;
        }

        tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }

        tfoot tr {
            background-color: #e5e7eb;
            font-weight: bold;
        }

        /* Layout simple con tabla de dos columnas */
        .row-table {
            width: 100%;
            border-collapse: collapse;
        }

        .row-table td {
            padding: 0;
            vertical-align: top;
            background: transparent;
        }

        /* Separador de seccion */
        .section-title {
            font-size: 8.5pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 4pt;
            margin-top: 10pt;
        }

        @yield('styles')
    </style>
</head>

<body>

    <div class="border-bottom pb-1 mb-2">
        @yield('header')
    </div>

    <div>
        @yield('content')
    </div>

    <div class="border-top pt-1 mt-3">
        @yield('footer')
    </div>

</body>

</html>
