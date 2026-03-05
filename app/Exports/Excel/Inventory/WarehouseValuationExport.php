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

class WarehouseValuationExport implements
    FromQuery,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithColumnFormatting,
    WithColumnWidths
{
    public function __construct(
        protected bool   $onlyWithStock  = true,
        protected string $orderBy        = 'warehouse',
        protected string $orderDirection = 'asc',
    ) {}

    public function query(): Builder
    {
        $query = DB::table('warehouses')
            ->leftJoin('raw_material_stocks as stocks',    'stocks.warehouse_id',  '=', 'warehouses.id')
            ->leftJoin('raw_material_batches as batches',  'batches.id',           '=', 'stocks.batch_id')
            ->select([
                'warehouses.id',
                'warehouses.name as warehouse_name',
                DB::raw('SUM(stocks.current_quantity * batches.received_unit_cost) as total_cost'),
            ])
            ->groupBy(['warehouses.id', 'warehouses.name'])
            ->when($this->onlyWithStock, fn(Builder $q): Builder => $q->having('total_cost', '>', 0));

        $dir = $this->sanitizedDirection();

        match ($this->orderBy) {
            'total_cost' => $query->orderByRaw("total_cost $dir"),
            default      => $query->orderBy('warehouses.name', $dir),
        };

        return $query;
    }

    /**
     * @return array<int, string>
     */
    public function headings(): array
    {
        return [
            'Almacén',     // A
            'Costo Total', // B
        ];
    }

    /**
     * @param object $row
     * @return array<int, mixed>
     */
    public function map(mixed $row): array
    {
        return [
            $row->warehouse_name, // A
            $row->total_cost,     // B
        ];
    }

    /**
     * @return array<string, int>
     */
    public function columnWidths(): array
    {
        return [
            'A' => 50, // Almacén
            'B' => 18, // Costo Total
        ];
    }

    /**
     * @return array<string, string>
     */
    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
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
            'warehouse'  => 'Almacén',
            'total_cost' => 'Costo Total',
        ];
    }
}
