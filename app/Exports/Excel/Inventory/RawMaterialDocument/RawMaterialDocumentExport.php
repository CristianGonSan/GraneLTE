<?php

namespace App\Exports\Excel\Inventory\RawMaterialDocument;

use App\Enums\Inventory\RawMaterialDocument\RawMaterialDocumentType;
use App\Exports\Excel\Inventory\RawMaterialDocument\Sheets\AdjustmentLinesSheet;
use App\Exports\Excel\Inventory\RawMaterialDocument\Sheets\DocumentSummarySheet;
use App\Exports\Excel\Inventory\RawMaterialDocument\Sheets\IssueLinesSheet;
use App\Exports\Excel\Inventory\RawMaterialDocument\Sheets\ReceiptLinesSheet;
use App\Exports\Excel\Inventory\RawMaterialDocument\Sheets\TransferLinesSheet;
use App\Models\Inventory\RawMaterialDocument;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class RawMaterialDocumentExport implements WithMultipleSheets
{
    public function __construct(private readonly int $id) {}

    /**
     * @return array<int, DocumentSummarySheet|ReceiptLinesSheet|IssueLinesSheet|TransferLinesSheet|AdjustmentLinesSheet>
     */
    public function sheets(): array
    {
        $document = RawMaterialDocument::findOrFail($this->id);

        $linesSheet = match ($document->type) {
            RawMaterialDocumentType::RECEIPT    => new ReceiptLinesSheet($this->id),
            RawMaterialDocumentType::ISSUE      => new IssueLinesSheet($this->id),
            RawMaterialDocumentType::TRANSFER   => new TransferLinesSheet($this->id),
            RawMaterialDocumentType::ADJUSTMENT => new AdjustmentLinesSheet($this->id),
        };

        return [
            new DocumentSummarySheet($this->id),
            $linesSheet,
        ];
    }
}
