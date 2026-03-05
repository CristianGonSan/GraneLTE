<?php

namespace App\Exports\Excel\Inventory;

use App\Models\Inventory\RawMaterialMovement;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RawMaterialMovementsExport implements
    FromQuery,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithColumnFormatting,
    WithColumnWidths
{
    /**
     * @param Builder<RawMaterialMovement> $query
     */
    public function __construct(
        protected Builder $query
    ) {}

    /**
     * @return Builder<RawMaterialMovement>
     */
    public function query(): Builder
    {
        return $this->query->with([
            'batch.material.category',
            'batch.material.unit',
            'warehouse',
            'document',
        ]);
    }

    public function headings(): array
    {
        return [
            // — transacción —
            'ID',               // A
            'Tipo',             // B
            'Fecha Efectiva',   // C
            // — identidad —
            'Material',         // D
            'Categoría',        // E
            // — ubicación —
            'Almacén',          // F
            // — unidad —
            'Unidad',           // G
            // — estado actual —
            'Cantidad',         // H
            // — histórico —
            'Costo Unitario',   // I
            'Costo Movido',     // J
            // — trazabilidad —
            'Código de Lote',   // K
            'No. Documento',    // L
        ];
    }

    /**
     * @param RawMaterialMovement $row
     */
    public function map(mixed $row): array
    {
        $batch    = $row->batch;
        $material = $batch->material;

        $totalCost = bcmul(
            (string) $row->quantity,
            (string) $batch->received_unit_cost,
            2
        );

        return [
            // — transacción —
            $row->id,                                   // A
            $row->type->label(),                        // B
            $row->effective_at->format('d/m/Y H:i'),   // C
            // — identidad —
            $material->name,                            // D
            $material->category->name,                  // E
            // — ubicación —
            $row->warehouse->name,                      // F
            // — unidad —
            $material->unit->symbol,                    // G
            // — cantidad —
            $row->quantity,                             // H
            // — costos —
            $batch->received_unit_cost,                 // I
            $totalCost,                                 // J
            // — trazabilidad —
            $batch->code,                               // K
            $row->document_id,                          // L
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 14, // ID
            'B' => 22, // Tipo
            'C' => 18, // Fecha Efectiva
            'D' => 30, // Material
            'E' => 22, // Categoría
            'F' => 22, // Almacén
            'G' => 10, // Unidad
            'H' => 14, // Cantidad
            'I' => 14, // Costo Unitario
            'J' => 14, // Costo Movido
            'K' => 30, // Código de Lote
            'L' => 14, // No. Documento
        ];
    }

    public function columnFormats(): array
    {
        return [
            'H' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2, // Cantidad
            'I' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2, // Costo Unitario
            'J' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2, // Costo Movido
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
