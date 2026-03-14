<?php

namespace App\Exports\Excel\Inventory;

use App\Models\Inventory\RawMaterialDocument;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RawMaterialDocumentsExport implements
    FromQuery,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithColumnFormatting,
    WithColumnWidths
{
    /**
     * @param Builder<RawMaterialDocument> $query
     */
    public function __construct(
        protected Builder $query
    ) {}

    /**
     * @return Builder<RawMaterialDocument>
     */
    public function query(): Builder
    {
        return $this->query->with([
            'responsible',
            'creator',
            'validator',
            'receipt.supplier',
        ]);
    }

    /**
     * @return array<int, string>
     */
    public function headings(): array
    {
        return [
            'ID',               // A
            'Tipo',             // B
            'Estado',           // C
            'Fecha Efectiva',   // D
            'Tipo Referencia',  // E
            'No. Referencia',   // F
            'Costo Total',      // G
            'Proveedor',        // H
            'Responsable',      // I
            'Creado por',       // J
            'Validado por',     // K
            'Fecha Validación', // L
            'Descripción',      // M
        ];
    }

    /**
     * @param RawMaterialDocument $row
     * @return array<int, mixed>
     */
    public function map(mixed $row): array
    {
        return [
            $row->id,                                               // A
            $row->type->label(),                                    // B
            $row->status->label(),                                  // C
            $row->effective_at->format('d/m/Y H:i'),                // D
            $row->reference_type ?? '—',                            // E
            $row->reference_number ?? '—',                          // F
            $row->total_cost,                                       // G
            $row->receipt?->supplier?->name ?? '—',                 // H
            $row->responsible?->name ?? '—',                        // I
            $row->creator->name,                                    // J
            $row->validator?->name ?? '—',                          // K
            $row->validated_at?->format('d/m/Y H:i') ?? '--/--/----',       // L
            $row->description ?? '—',                               // M
        ];
    }

    /**
     * @return array<string, int>
     */
    public function columnWidths(): array
    {
        return [
            'A' => 10, // ID
            'B' => 18, // Tipo
            'C' => 16, // Estado
            'D' => 18, // Fecha Efectiva
            'E' => 18, // Tipo Referencia
            'F' => 18, // No. Referencia
            'G' => 14, // Costo Total
            'H' => 28, // Proveedor
            'I' => 26, // Responsable
            'J' => 26, // Creado por
            'K' => 26, // Validado por
            'L' => 18, // Fecha Validación
            'M' => 40, // Descripción
        ];
    }

    /**
     * @return array<string, string>
     */
    public function columnFormats(): array
    {
        return [
            'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2, // Costo Total
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
