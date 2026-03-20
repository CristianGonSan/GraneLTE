<?php

namespace App\Exports\Excel\Inventory\RawMaterialDocument\Sheets;

use App\Models\Inventory\RawMaterialDocument;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DocumentSummarySheet implements FromArray, WithTitle, WithStyles, ShouldAutoSize
{
    public function __construct(private readonly int $id) {}

    /**
     * @return array<int, array<int, string>>
     */
    public function array(): array
    {
        $doc = RawMaterialDocument::with(['responsible', 'creator', 'validator', 'receipt.supplier'])
            ->findOrFail($this->id);

        return [
            ['Tipo',              $doc->type->label()],
            ['Estado',            $doc->status->label()],
            ['Fecha efectiva',    $doc->effective_at->format('d/m/Y')],
            ['Tipo referencia',   $doc->reference_type ?? '—'],
            ['Número referencia', $doc->reference_number ?? '—'],
            ['Proveedor',         $doc->receipt?->supplier?->name ?? 'N/A'],
            ['Responsable',       $doc->responsible?->name ?? '—'],
            ['Descripción',       $doc->description ?? '—'],
            ['Costo total',       $doc->total_cost ?? '—'],
            ['Creado por',        $doc->creator->name],
            ['Validado por',      $doc->validator?->name ?? '—'],
            ['Fecha validación',  $doc->validated_at?->format('d/m/Y h:i a') ?? '--/--/---- -:- -'],
        ];
    }

    public function title(): string
    {
        return 'Información general';
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function styles(Worksheet $sheet): array
    {
        return [
            'A' => ['font' => ['bold' => true]],
        ];
    }
}
