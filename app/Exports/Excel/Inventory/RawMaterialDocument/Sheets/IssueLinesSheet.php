<?php

namespace App\Exports\Excel\Inventory\RawMaterialDocument\Sheets;

use App\Models\Inventory\RawMaterialIssueLine;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class IssueLinesSheet implements FromQuery, WithTitle, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    public function __construct(private readonly int $id) {}

    /**
     * @return Builder<RawMaterialIssueLine>
     */
    public function query(): Builder
    {
        return RawMaterialIssueLine::with(['batch.material', 'warehouse'])
            ->where('document_id', $this->id);
    }

    public function title(): string
    {
        return 'Líneas de salida';
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
            'Cantidad',
            'Costo total',
        ];
    }

    /**
     * @param RawMaterialIssueLine $row
     * @return array<int, mixed>
     */
    public function map(mixed $row): array
    {
        return [
            $row->batch->material->name,
            $row->warehouse->name,
            $row->batch->code,
            $row->quantity,
            $row->totalCost(),
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
