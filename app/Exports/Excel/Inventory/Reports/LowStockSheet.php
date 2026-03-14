<?php

namespace App\Exports\Excel\Inventory\Reports;

use App\Models\Inventory\RawMaterial;
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

class LowStockSheet implements WithTitle, FromQuery, WithHeadings, WithMapping, WithStyles, WithColumnFormatting, WithColumnWidths
{
    public function title(): string
    {
        return 'Stock Bajo';
    }

    /**
     * @return Builder<RawMaterial>
     */
    public function query(): Builder
    {
        return RawMaterial::active()
            ->with(['category', 'unit'])
            ->where('minimum_stock', '>', 0)
            ->whereColumn('current_quantity', '<', 'minimum_stock')->orderByRaw("(minimum_stock - current_quantity) desc");
    }

    /**
     * @return array<int, string>
     */
    public function headings(): array
    {
        return [
            'Material',      // A
            'Abreviatura',   // B
            'Categoria',     // C
            'Unidad',        // D
            'Stock Minimo',  // E
            'Stock Actual',  // F
            'Faltante',    // G
        ];
    }

    /**
     * @param RawMaterial $row
     * @return array<int, mixed>
     */
    public function map(mixed $row): array
    {
        return [
            $row->name,                                                                               // A
            $row->abbreviation,                                                                       // B
            $row->category->name,                                                                     // C
            $row->unit->symbol,                                                                       // D
            $row->minimum_stock,                                                                      // E
            $row->current_quantity,                                                                   // F
            bcsub($row->minimum_stock, $row->current_quantity, 3),                                    // G
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
            'A' => 35,
            'B' => 15,
            'C' => 20,
            'D' => 10,
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
