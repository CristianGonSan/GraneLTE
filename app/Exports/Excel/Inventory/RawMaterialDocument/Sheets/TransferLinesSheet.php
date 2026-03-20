<?php

namespace App\Exports\Excel\Inventory\RawMaterialDocument\Sheets;

use App\Models\Inventory\RawMaterialTransferLine;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TransferLinesSheet implements FromQuery, WithTitle, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    public function __construct(private readonly int $id) {}

    /**
     * @return Builder<RawMaterialTransferLine>
     */
    public function query(): Builder
    {
        return RawMaterialTransferLine::with([
            'originStock.batch.material',
            'originStock.warehouse',
            'warehouseDest',
        ])->where('document_id', $this->id);
    }

    public function title(): string
    {
        return 'Líneas de transferencia';
    }

    /**
     * @return array<int, string>
     */
    public function headings(): array
    {
        return [
            'Material',
            'Lote',
            'Almacén origen',
            'Almacén destino',
            'Cantidad',
        ];
    }

    /**
     * @param RawMaterialTransferLine $row
     * @return array<int, mixed>
     */
    public function map(mixed $row): array
    {
        return [
            $row->originStock->batch->material->name,
            $row->originStock->batch->code,
            $row->originStock->warehouse->name,
            $row->warehouseDest->name,
            $row->quantity,
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
