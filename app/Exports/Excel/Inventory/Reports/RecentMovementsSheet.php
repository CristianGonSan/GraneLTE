<?php

namespace App\Exports\Excel\Inventory\Reports;

use App\Models\Inventory\RawMaterialMovement;
use Carbon\Carbon;
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

class RecentMovementsSheet implements WithTitle, FromQuery, WithHeadings, WithMapping, WithStyles, WithColumnFormatting, WithColumnWidths
{
    public function __construct(
        private readonly Carbon $from,
        private readonly Carbon $to,
    ) {}

    public function title(): string
    {
        return 'Movimientos Recientes';
    }

    /**
     * @return Builder<RawMaterialMovement>
     */
    public function query(): Builder
    {
        return RawMaterialMovement::with(['batch.material', 'warehouse'])
            ->whereBetween('effective_at', [
                $this->from->copy()->startOfDay(),
                $this->to->copy()->endOfDay(),
            ])
            ->orderBy('effective_at', 'desc');
    }

    /**
     * @return array<int, string>
     */
    public function headings(): array
    {
        return [
            'Fecha',     // A
            'Tipo',      // B
            'Material',  // C
            'Lote',      // D
            'Almacen',   // E
            'Cantidad',  // F
        ];
    }

    /**
     * @param RawMaterialMovement $row
     * @return array<int, mixed>
     */
    public function map(mixed $row): array
    {
        return [
            $row->effective_at->format('d/m/Y H:i'), // A
            $row->type->label(),                      // B
            $row->batch->material->name,              // C
            $row->batch->code,                        // D
            $row->warehouse->name,                    // E
            $row->quantity,                           // F
        ];
    }

    /**
     * @return array<string, string>
     */
    public function columnFormats(): array
    {
        return [
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
        ];
    }

    /**
     * @return array<string, int|float>
     */
    public function columnWidths(): array
    {
        return [
            'A' => 18,
            'B' => 25,
            'C' => 35,
            'D' => 24,
            'E' => 25,
            'F' => 15,
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
