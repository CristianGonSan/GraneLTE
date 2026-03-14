<?php

namespace App\Exports\Excel\Inventory\Reports;

use App\Models\Inventory\RawMaterialStock;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StockByWarehouseSheet implements WithTitle, FromQuery, WithHeadings, WithMapping, WithStyles, WithColumnFormatting, WithColumnWidths
{
    public function title(): string
    {
        return 'Existencias por Almacen';
    }

    /**
     * @return Builder<RawMaterialStock>
     */
    public function query(): Builder
    {
        return RawMaterialStock::available()
            ->with(['batch.material', 'batch.supplier', 'warehouse'])
            ->join('warehouses', 'warehouses.id', '=', 'raw_material_stocks.warehouse_id')
            ->orderBy('warehouses.name');
    }

    /**
     * @return array<int, string>
     */
    public function headings(): array
    {
        return [
            'Almacen',        // A
            'Material',       // B
            'Lote',           // C
            'Proveedor',      // D
            'Cant. Actual',   // E
            'Costo Unitario', // F
            'Costo Total',    // G
        ];
    }

    /**
     * @param RawMaterialStock $row
     * @return array<int, mixed>
     */
    public function map(mixed $row): array
    {
        return [
            $row->warehouse->name,            // A
            $row->batch->material->name,      // B
            $row->batch->code,                // C
            $row->batch->supplier->name,      // D
            $row->current_quantity,           // E
            $row->batch->received_unit_cost,  // F
            $row->current_cost,               // G
        ];
    }

    /**
     * @return array<string, string>
     */
    public function columnFormats(): array
    {
        return [
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
        ];
    }

    /**
     * @return array<string, int|float>
     */
    public function columnWidths(): array
    {
        return [
            'A' => 25,
            'B' => 35,
            'C' => 24,
            'D' => 25,
            'E' => 15,
            'F' => 15,
            'G' => 15,
        ];
    }

    /**
     * @return array<int|string, mixed>
     */
    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
