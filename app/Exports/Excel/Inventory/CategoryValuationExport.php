<?php

namespace App\Exports\Excel\Inventory;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CategoryValuationExport implements
    FromQuery,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithColumnFormatting,
    WithColumnWidths
{
    public function __construct(
        protected bool   $onlyWithStock  = true,
        protected string $orderBy        = 'category',
        protected string $orderDirection = 'asc',
    ) {}

    public function query(): Builder
    {
        $query = DB::table('categories')
            ->join('raw_materials as materials',      'materials.category_id', '=', 'categories.id')
            ->leftJoin('raw_material_batches as batches', 'batches.material_id', '=', 'materials.id')
            ->select([
                'categories.id',
                'categories.name as category_name',
                DB::raw('COUNT(DISTINCT materials.id)                               as distinct_materials'),
                DB::raw('SUM(batches.current_quantity * batches.received_unit_cost) as total_cost'),
            ])
            ->groupBy(['categories.id', 'categories.name'])
            ->when($this->onlyWithStock, fn(Builder $q): Builder => $q->having('total_cost', '>', 0));

        $dir = $this->sanitizedDirection();

        match ($this->orderBy) {
            'distinct_materials' => $query->orderByRaw("distinct_materials $dir"),
            'total_cost'         => $query->orderByRaw("total_cost $dir"),
            default              => $query->orderBy('categories.name', $dir),
        };

        return $query;
    }

    /**
     * @return array<int, string>
     */
    public function headings(): array
    {
        return [
            'Categoría',   // A
            'Materiales',  // B
            'Costo Total', // C
        ];
    }

    /**
     * @param object $row
     * @return array<int, mixed>
     */
    public function map(mixed $row): array
    {
        return [
            $row->category_name,      // A
            $row->distinct_materials, // B
            $row->total_cost,         // C
        ];
    }

    /**
     * @return array<string, int>
     */
    public function columnWidths(): array
    {
        return [
            'A' => 50, // Categoría
            'B' => 14, // Materiales
            'C' => 18, // Costo Total
        ];
    }

    /**
     * @return array<string, string>
     */
    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
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

    private function sanitizedDirection(): string
    {
        return $this->orderDirection === 'desc' ? 'desc' : 'asc';
    }

    /**
     * @return array<string, string>
     */
    public static function sortableColumns(): array
    {
        return [
            'category'           => 'Categoría',
            'distinct_materials' => 'Materiales',
            'total_cost'         => 'Costo Total',
        ];
    }
}
