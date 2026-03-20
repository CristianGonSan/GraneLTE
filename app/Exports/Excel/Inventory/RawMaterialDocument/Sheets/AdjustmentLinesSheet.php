<?php

namespace App\Exports\Excel\Inventory\RawMaterialDocument\Sheets;

use App\Models\Inventory\RawMaterialAdjustmentLine;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AdjustmentLinesSheet implements FromQuery, WithTitle, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    public function __construct(private readonly int $id) {}

    /**
     * @return Builder<RawMaterialAdjustmentLine>
     */
    public function query(): Builder
    {
        return RawMaterialAdjustmentLine::with([
            'stock.batch.material',
            'stock.warehouse',
        ])->where('document_id', $this->id);
    }

    public function title(): string
    {
        return 'Líneas de ajuste';
    }

    /**
     * @return array<int, string>
     */
    public function headings(): array
    {
        return [
            'Material',
            'Almacén',
            'Lote',
            'Cantidad teórica',
            'Cantidad contada',
            'Diferencia',
        ];
    }

    /**
     * @param RawMaterialAdjustmentLine $row
     * @return array<int, mixed>
     */
    public function map(mixed $row): array
    {
        return [
            $row->stock->batch->material->name,
            $row->stock->warehouse->name,
            $row->stock->batch->code,
            $row->theoretical_quantity,
            $row->counted_quantity,
            $row->difference_quantity,
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
