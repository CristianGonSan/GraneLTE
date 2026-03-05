<?php

namespace App\Exports\Excel\Inventory;

use App\Models\Inventory\RawMaterial;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RawMaterialsExport implements
    FromQuery,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithColumnFormatting,
    WithColumnWidths
{
    /**
     * @param Builder<RawMaterial> $query
     */
    public function __construct(
        protected Builder $query
    ) {}

    /**
     * @return Builder<RawMaterial>
     */
    public function query(): Builder
    {
        return $this->query->with([
            'unit',
            'category',
        ]);
    }

    public function headings(): array
    {
        return [
            // — identidad —
            'Material',         // A
            // — agrupación —
            'Categoría',        // B
            // — unidad —
            'Unidad',           // C
            // — estado actual —
            'Cant. Actual',     // D
            'Costo Actual',     // E
            'Cant. Mínima',     // F
            // — metadata —
            'Stock Bajo',       // G
            'Activo',           // H
        ];
    }

    /**
     * @param RawMaterial $row
     */
    public function map(mixed $row): array
    {
        return [
            // — identidad —
            $row->name,                                 // A
            // — agrupación —
            $row->category->name,                       // B
            // — unidad —
            $row->unit->symbol,                         // C
            // — estado actual —
            $row->current_quantity,                     // D
            $row->current_cost,                         // E
            $row->minimum_stock,                        // F
            // — metadata —
            $row->isLowStock() ? 'Sí' : 'No',          // G
            $row->is_active ? 'Activo' : 'Inactivo',   // H
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 32, // Material
            'B' => 22, // Categoría
            'C' => 10, // Unidad
            'D' => 14, // Cant. Actual
            'E' => 16, // Costo Actual
            'F' => 14, // Cant. Mínima
            'G' => 12, // Stock Bajo
            'H' => 10, // Activo
        ];
    }

    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2, // Cant. Actual
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2, // Costo Actual
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2, // Cant. Mínima
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
