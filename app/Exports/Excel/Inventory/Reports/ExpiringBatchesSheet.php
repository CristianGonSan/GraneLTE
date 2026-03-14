<?php

namespace App\Exports\Excel\Inventory\Reports;

use App\Models\Inventory\RawMaterialBatch;
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

class ExpiringBatchesSheet implements WithTitle, FromQuery, WithHeadings, WithMapping, WithStyles, WithColumnFormatting, WithColumnWidths
{
    public function __construct(
        private readonly int $days = 30,
    ) {}

    public function title(): string
    {
        return 'Vencimientos';
    }

    /**
     * @return Builder<RawMaterialBatch>
     */
    public function query(): Builder
    {
        return RawMaterialBatch::expiring($this->days)
            ->with(['material', 'supplier']);
    }

    /**
     * @return array<int, string>
     */
    public function headings(): array
    {
        return [
            'Codigo Lote',       // A
            'Codigo Externo',    // B
            'Material',          // C
            'Proveedor',         // D
            'Cant. Actual',      // E
            'Costo Unitario',    // F
            'Fec. Recepcion',    // G
            'Fec. Vencimiento',  // H
            'Dias Restantes',    // I
            'Estado',            // J
        ];
    }

    /**
     * @param RawMaterialBatch $row
     * @return array<int, mixed>
     */
    public function map(mixed $row): array
    {
        return [
            $row->batch_code,                                           // A
            $row->external_batch_code ?? '—',                          // B
            $row->material->name,                                       // C
            $row->supplier->name,                                       // D
            $row->current_quantity,                                     // E
            $row->received_unit_cost,                                   // F
            $row->received_at->format('d/m/Y'),                        // G
            $row->expiration_date?->format('d/m/Y') ?? '--/--/----',   // H
            $this->daysUntilExpiration($row),                           // I
            $row->isExpired() ? 'Vencido' : 'Por vencer',              // J
        ];
    }

    /**
     * Retorna los dias restantes hasta la fecha de vencimiento como entero,
     * o 0 si el lote ya esta vencido. Retorna null si no tiene fecha de vencimiento.
     */
    private function daysUntilExpiration(RawMaterialBatch $batch): ?int
    {
        if ($batch->expiration_date === null) {
            return null;
        }

        if ($batch->isExpired()) {
            return 0;
        }

        return (int) now()->startOfDay()->diffInDays($batch->expiration_date->startOfDay());
    }

    /**
     * @return array<string, string>
     */
    public function columnFormats(): array
    {
        return [
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'I' => NumberFormat::FORMAT_NUMBER,
        ];
    }

    /**
     * @return array<string, int|float>
     */
    public function columnWidths(): array
    {
        return [
            'A' => 24,
            'B' => 24,
            'C' => 35,
            'D' => 25,
            'E' => 15,
            'F' => 15,
            'G' => 18,
            'H' => 18,
            'I' => 15,
            'J' => 12,
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
