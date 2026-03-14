<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Material: {{ $material->name }}</title>
</head>

<body style="font-family: 'DejaVu Sans', sans-serif; font-size: 12px; color: #111111; margin: 30px 40px;">

    {{-- Encabezado --}}
    <table width="100%" cellpadding="0" cellspacing="0" style="border-bottom: 2px solid #111111; margin-bottom: 16px;">
        <tr>
            <td style="padding-bottom: 8px;">
                <p style="font-size: 18px; font-weight: bold; margin: 0;">FICHA DE MATERIA PRIMA</p>
                <p style="font-size: 12px; color: #555555; margin: 4px 0 0 0;">{{ $material->name }} &mdash;
                    {{ $material->abbreviation }}</p>
            </td>
            <td width="160"
                style="padding-bottom: 8px; text-align: right; font-size: 10px; color: #777777; vertical-align: bottom;">
                Generado el {{ now()->format('d/m/Y H:i') }}
            </td>
        </tr>
    </table>

    {{-- Titulo seccion datos generales --}}
    <p
        style="font-size: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; margin: 0 0 6px 0; padding: 0;">
        Datos Generales
    </p>

    {{-- Datos generales --}}
    <table width="100%" cellpadding="8" cellspacing="0" style="border-collapse: collapse; margin-bottom: 20px;">
        <tr>
            <td width="25%" style="border: 1px solid #cccccc;">
                <p
                    style="font-size: 9px; font-weight: bold; text-transform: uppercase; color: #888888; margin: 0 0 3px 0;">
                    Nombre</p>
                <p style="font-size: 12px; margin: 0;">{{ $material->name }}</p>
            </td>
            <td width="25%" style="border: 1px solid #cccccc;">
                <p
                    style="font-size: 9px; font-weight: bold; text-transform: uppercase; color: #888888; margin: 0 0 3px 0;">
                    Abreviatura</p>
                <p style="font-size: 12px; margin: 0;">{{ $material->abbreviation }}</p>
            </td>
            <td width="25%" style="border: 1px solid #cccccc;">
                <p
                    style="font-size: 9px; font-weight: bold; text-transform: uppercase; color: #888888; margin: 0 0 3px 0;">
                    Categoria</p>
                <p style="font-size: 12px; margin: 0;">{{ $material->category->name }}</p>
            </td>
            <td width="25%" style="border: 1px solid #cccccc;">
                <p
                    style="font-size: 9px; font-weight: bold; text-transform: uppercase; color: #888888; margin: 0 0 3px 0;">
                    Unidad</p>
                <p style="font-size: 12px; margin: 0;">{{ $material->unit->name }} ({{ $material->unit->symbol }})</p>
            </td>
        </tr>
        <tr>
            <td width="25%" style="border: 1px solid #cccccc;">
                <p
                    style="font-size: 9px; font-weight: bold; text-transform: uppercase; color: #888888; margin: 0 0 3px 0;">
                    Estado</p>
                <p style="font-size: 12px; margin: 0;">
                    @if ($material->is_active)
                        <span
                            style="background-color: #dcfce7; color: #15803d; font-size: 10px; font-weight: bold; padding: 2px 6px;">Activo</span>
                    @else
                        <span
                            style="background-color: #fee2e2; color: #b91c1c; font-size: 10px; font-weight: bold; padding: 2px 6px;">Inactivo</span>
                    @endif
                </p>
            </td>
            <td width="25%" style="border: 1px solid #cccccc;">
                <p
                    style="font-size: 9px; font-weight: bold; text-transform: uppercase; color: #888888; margin: 0 0 3px 0;">
                    Stock Minimo</p>
                <p style="font-size: 12px; margin: 0;">{{ number_format($material->minimum_stock, 3) }}
                    {{ $material->unit->symbol }}</p>
            </td>
            <td width="25%" style="border: 1px solid #cccccc;">
                <p
                    style="font-size: 9px; font-weight: bold; text-transform: uppercase; color: #888888; margin: 0 0 3px 0;">
                    Cantidad Actual</p>
                <p style="font-size: 12px; margin: 0;">
                    @if ($material->isLowStock())
                        <span
                            style="background-color: #fef9c3; color: #a16207; font-size: 10px; font-weight: bold; padding: 2px 6px;">
                            {{ number_format($material->current_quantity, 3) }} {{ $material->unit->symbol }}
                        </span>
                    @else
                        {{ number_format($material->current_quantity, 3) }} {{ $material->unit->symbol }}
                    @endif
                </p>
            </td>
            <td width="25%" style="border: 1px solid #cccccc;">
                <p
                    style="font-size: 9px; font-weight: bold; text-transform: uppercase; color: #888888; margin: 0 0 3px 0;">
                    Costo Total</p>
                <p style="font-size: 12px; margin: 0;">${{ number_format($material->current_cost, 2) }}</p>
            </td>
        </tr>
        @if ($material->description)
            <tr>
                <td colspan="4" style="border: 1px solid #cccccc;">
                    <p
                        style="font-size: 9px; font-weight: bold; text-transform: uppercase; color: #888888; margin: 0 0 3px 0;">
                        Descripcion</p>
                    <p style="font-size: 12px; margin: 0;">{{ $material->description }}</p>
                </td>
            </tr>
        @endif
    </table>

    {{-- Titulo seccion lotes --}}
    <p style="font-size: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; margin: 0 0 6px 0;">
        Lotes &mdash; {{ $material->batches->count() }} registros
    </p>

    @if ($material->batches->isEmpty())
        <p style="font-size: 11px; color: #aaaaaa;">No hay lotes registrados para este material.</p>
    @else
        @php
            $totalReceivedQty = 0;
            $totalCurrentQty = 0;
            $totalCurrentCost = 0;
        @endphp

        <table width="100%" cellpadding="6" cellspacing="0" style="border-collapse: collapse; table-layout: fixed;">
            <thead>
                <tr style="background-color: #374151; color: #ffffff;">
                    <th width="4%"
                        style="font-size: 9px; text-align: center; border: 1px solid #374151; padding: 6px 4px;">#</th>
                    <th width="15%"
                        style="font-size: 9px; text-align: left;   border: 1px solid #374151; padding: 6px 6px;">Codigo
                    </th>
                    <th width="17%"
                        style="font-size: 9px; text-align: left;   border: 1px solid #374151; padding: 6px 6px;">
                        Proveedor</th>
                    <th width="9%"
                        style="font-size: 9px; text-align: center; border: 1px solid #374151; padding: 6px 4px;">
                        Recepcion</th>
                    <th width="10%"
                        style="font-size: 9px; text-align: center; border: 1px solid #374151; padding: 6px 4px;">
                        Vencimiento</th>
                    <th width="11%"
                        style="font-size: 9px; text-align: right;  border: 1px solid #374151; padding: 6px 6px;">Cant.
                        Recibida</th>
                    <th width="11%"
                        style="font-size: 9px; text-align: right;  border: 1px solid #374151; padding: 6px 6px;">Costo
                        Unit.</th>
                    <th width="11%"
                        style="font-size: 9px; text-align: right;  border: 1px solid #374151; padding: 6px 6px;">Cant.
                        Actual</th>
                    <th width="12%"
                        style="font-size: 9px; text-align: right;  border: 1px solid #374151; padding: 6px 6px;">Costo
                        Actual</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($material->batches as $i => $batch)
                    @php
                        $isExpired = $batch->isExpired();
                        $isEmpty = $batch->current_quantity <= 0;
                        $rowBg = $i % 2 === 0 ? '#ffffff' : '#f7f7f7';
                        $textColor = $isExpired ? '#b91c1c' : ($isEmpty ? '#aaaaaa' : '#111111');

                        $totalReceivedQty += $batch->received_quantity;
                        $totalCurrentQty += $batch->current_quantity;
                        $totalCurrentCost += $batch->current_cost;
                    @endphp
                    <tr style="background-color: {{ $rowBg }};">
                        <td
                            style="font-size: 10px; text-align: center; border: 1px solid #cccccc; color: {{ $textColor }};">
                            {{ $i + 1 }}</td>
                        <td
                            style="font-size: 10px; text-align: left;   border: 1px solid #cccccc; color: {{ $textColor }};">
                            {{ $batch->code }}</td>
                        <td
                            style="font-size: 10px; text-align: left;   border: 1px solid #cccccc; color: {{ $textColor }};">
                            {{ $batch->supplier->name }}</td>
                        <td
                            style="font-size: 10px; text-align: center; border: 1px solid #cccccc; color: {{ $textColor }};">
                            {{ $batch->received_at->format('d/m/Y') }}</td>
                        <td
                            style="font-size: 10px; text-align: center; border: 1px solid #cccccc; color: {{ $textColor }};">
                            @if ($batch->expiration_date)
                                {{ $batch->expiration_date->format('d/m/Y') }}
                                @if ($isExpired)
                                    (Venc.)
                                @endif
                            @else
                                &mdash;
                            @endif
                        </td>
                        <td
                            style="font-size: 10px; text-align: right; border: 1px solid #cccccc; color: {{ $textColor }};">
                            {{ number_format($batch->received_quantity, 3) }}</td>
                        <td
                            style="font-size: 10px; text-align: right; border: 1px solid #cccccc; color: {{ $textColor }};">
                            ${{ number_format($batch->received_unit_cost, 2) }}</td>
                        <td
                            style="font-size: 10px; text-align: right; border: 1px solid #cccccc; color: {{ $textColor }};">
                            {{ number_format($batch->current_quantity, 3) }}</td>
                        <td
                            style="font-size: 10px; text-align: right; border: 1px solid #cccccc; color: {{ $textColor }};">
                            ${{ number_format($batch->current_cost, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background-color: #eeeeee;">
                    <td colspan="5"
                        style="font-size: 10px; font-weight: bold; border: 1px solid #bbbbbb; padding: 6px 6px;">Totales
                    </td>
                    <td
                        style="font-size: 10px; font-weight: bold; text-align: right; border: 1px solid #bbbbbb; padding: 6px 6px;">
                        {{ number_format($totalReceivedQty, 3) }}</td>
                    <td style="border: 1px solid #bbbbbb;"></td>
                    <td
                        style="font-size: 10px; font-weight: bold; text-align: right; border: 1px solid #bbbbbb; padding: 6px 6px;">
                        {{ number_format($totalCurrentQty, 3) }}</td>
                    <td
                        style="font-size: 10px; font-weight: bold; text-align: right; border: 1px solid #bbbbbb; padding: 6px 6px;">
                        ${{ number_format($totalCurrentCost, 2) }}</td>
                </tr>
            </tfoot>
        </table>

        {{-- Leyenda --}}
        <table width="100%" cellpadding="5" cellspacing="0" style="border-collapse: collapse; margin-top: 8px;">
            <tr>
                <td width="50%" style="border: 1px solid #cccccc;">
                    <table cellpadding="0" cellspacing="0">
                        <tr>
                            <td
                                style="width: 14px; height: 14px; background-color: #fee2e2; border: 1px solid #cccccc;">
                            </td>
                            <td style="padding-left: 6px; font-size: 10px; color: #b91c1c;">Lote vencido</td>
                        </tr>
                    </table>
                </td>
                <td width="50%" style="border: 1px solid #cccccc;">
                    <table cellpadding="0" cellspacing="0">
                        <tr>
                            <td
                                style="width: 14px; height: 14px; background-color: #eeeeee; border: 1px solid #cccccc;">
                            </td>
                            <td style="padding-left: 6px; font-size: 10px; color: #aaaaaa;">Lote sin existencias</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    @endif

    {{-- Pie --}}
    <table width="100%" cellpadding="0" cellspacing="0" style="margin-top: 28px; border-top: 1px solid #cccccc;">
        <tr>
            <td style="padding-top: 7px; text-align: center; font-size: 9px; color: #aaaaaa;">
                Documento generado automaticamente &mdash; Valido como referencia interna.
            </td>
        </tr>
    </table>

</body>

</html>
