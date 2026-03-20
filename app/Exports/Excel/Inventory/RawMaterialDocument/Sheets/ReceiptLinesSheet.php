<?php

namespace App\Exports\Excel\Inventory\RawMaterialDocument\Sheets;

use App\Models\Inventory\RawMaterialReceiptLine;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReceiptLinesSheet implements FromQuery, WithTitle, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    public function __construct(private readonly int $id) {}

    /**
     * @return Builder<RawMaterialReceiptLine>
     */
    public function query(): Builder
    {
        return RawMaterialReceiptLine::with(['material', 'warehouse'])
            ->where('document_id', $this->id);
    }

    public function title(): string
    {
        return 'Líneas de entrada';
    }

    /**
     * @return array<int, string>
     */
    public function headings(): array
    {
        return [
            'Material',
            'Almacén',
            'Lote externo',
            'Cantidad recibida',
            'Costo unitario',
            'Costo total',
            'Fecha de vencimiento',
        ];
    }

    /**
     * @param RawMaterialReceiptLine $row
     * @return array<int, mixed>
     */
    public function map(mixed $row): array
    {
        return [
            $row->material->name,
            $row->warehouse->name,
            $row->external_batch_code ?? '—',
            $row->received_quantity,
            $row->received_unit_cost,
            $row->received_total_cost,
            $row->expiration_date?->format('d/m/Y') ?? '—',
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
