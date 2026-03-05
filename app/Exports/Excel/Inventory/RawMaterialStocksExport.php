<?php

namespace App\Exports\Excel\Inventory;

use App\Models\Inventory\RawMaterialStock;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RawMaterialStocksExport implements
    FromQuery,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithColumnFormatting,
    WithColumnWidths
{
    /**
     * @param Builder<RawMaterialStock> $query
     */
    public function __construct(
        protected Builder $query
    ) {}

    /**
     * @return Builder<RawMaterialStock>
     */
    public function query(): Builder
    {
        return $this->query->with([
            'batch.material.unit',
            'batch.material.category',
            'warehouse',
        ]);
    }

    public function headings(): array
    {
        return [
            // — identidad —
            'Material',             // A
            // — agrupación —
            'Categoría',            // B
            // — ubicación —
            'Almacén',              // C
            // — unidad —
            'Unidad',               // D
            // — estado actual —
            'Cant. Actual',         // E
            'Costo Actual',         // F
            // — histórico —
            'Costo Unitario',       // G
            // — trazabilidad —
            'Código de Lote',       // H
            'Fec. Recepción',       // I
            'Fec. Vencimiento',     // J
        ];
    }

    /**
     * @param RawMaterialStock $row
     */
    public function map(mixed $row): array
    {
        $batch    = $row->batch;
        $material = $batch->material;

        return [
            // — identidad —
            $material->name,                                                // A
            // — agrupación —
            $material->category->name,                                      // B
            // — ubicación —
            $row->warehouse->name,                                          // C
            // — unidad —
            $material->unit->symbol,                                        // D
            // — estado actual —
            $row->current_quantity,                                         // E
            $row->current_cost,                                             // F
            // — histórico —
            $batch->received_unit_cost,                                     // G
            // — trazabilidad —
            $batch->code,                                                   // H
            $batch->received_at->format('d/m/Y'),                          // I
            $batch->expiration_date?->format('d/m/Y') ?? '--/--/----',     // J
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 30, // Material
            'B' => 18, // Categoría
            'C' => 22, // Almacén
            'D' => 10, // Unidad
            'E' => 14, // Cant. Actual
            'F' => 14, // Costo Actual
            'G' => 14, // Costo Unitario
            'H' => 30, // Código de Lote
            'I' => 16, // Fec. Recepción
            'J' => 16, // Fec. Vencimiento
        ];
    }

    public function columnFormats(): array
    {
        return [
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2, // Cant. Actual
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2, // Costo Actual
            'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2, // Costo Unitario
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
