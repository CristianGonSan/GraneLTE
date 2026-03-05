<?php

namespace App\Exports\Excel\Inventory;

use App\Models\Inventory\RawMaterialBatch;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RawMaterialBatchesExport implements
    FromQuery,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithColumnFormatting,
    WithColumnWidths
{
    /**
     * @param Builder<RawMaterialBatch> $query
     */
    public function __construct(
        protected Builder $query
    ) {}

    /**
     * @return Builder<RawMaterialBatch>
     */
    public function query(): Builder
    {
        return $this->query->with([
            'material.unit',
            'material.category',
            'supplier',
        ]);
    }

    public function headings(): array
    {
        return [
            // — identidad —
            'Código de Lote',       // A
            'Material',             // B
            // — agrupación —
            'Categoría',            // C
            // — unidad —
            'Unidad',               // D
            // — estado actual —
            'Cant. Actual',         // E
            'Costo Actual',         // F
            // — histórico —
            'Cant. Recibida',       // G
            'Costo Unitario',       // H
            'Costo Total Recibido', // I
            // — trazabilidad —
            'Proveedor',            // J
            'Fecha de Recepción',   // K
            'Fecha de Vencimiento', // L
            // — vigencia —
            'Por Vencer',     // M
        ];
    }

    /**
     * @param RawMaterialBatch $row
     */
    public function map(mixed $row): array
    {
        $material = $row->material;

        return [
            // — identidad —
            $row->code,                                               // A
            $material->name,                                          // B
            // — agrupación —
            $material->category->name,                               // C
            // — unidad —
            $material->unit->symbol,                                  // D
            // — estado actual —
            $row->current_quantity,                                   // E
            $row->current_cost,                                       // F
            // — histórico —
            $row->received_quantity,                                  // G
            $row->received_unit_cost,                                 // H
            $row->received_total_cost,                                // I
            // — trazabilidad —
            $row->supplier->name,                                     // J
            $row->received_at->format('d/m/Y'),                      // K
            $row->expiration_date?->format('d/m/Y') ?? '--/--/----', // L
            // — vigencia —
            $this->daysUntilExpiration($row),                         // M
        ];
    }

    /**
     * Retorna los días restantes hasta la fecha de vencimiento como texto,
     * "Caducado" si ya venció, o "Imperecedero" si no tiene fecha de vencimiento.
     */
    private function daysUntilExpiration(RawMaterialBatch $batch): string
    {
        if (!$batch->expiration_date) {
            return 'Imperecedero';
        }

        if ($batch->isExpired()) {
            return 'Caducado';
        }

        $days = (int) now()->startOfDay()->diffInDays($batch->expiration_date->startOfDay());

        return "$days días";
    }

    public function columnWidths(): array
    {
        return [
            'A' => 30, // Código de Lote
            'B' => 32, // Material
            'C' => 22, // Categoría
            'D' => 10, // Unidad
            'E' => 14, // Cant. Actual
            'F' => 16, // Costo Actual
            'G' => 16, // Cant. Recibida
            'H' => 16, // Costo Unitario
            'I' => 20, // Costo Total Recibido
            'J' => 28, // Proveedor
            'K' => 18, // Fecha de Recepción
            'L' => 20, // Fecha de Vencimiento
            'M' => 16, // Días para Vencer
        ];
    }

    public function columnFormats(): array
    {
        return [
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2, // Cant. Actual
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2, // Costo Actual
            'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2, // Cant. Recibida
            'H' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2, // Costo Unitario
            'I' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2, // Costo Total Recibido
            'M' => NumberFormat::FORMAT_TEXT,                    // Días para Vencer
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
