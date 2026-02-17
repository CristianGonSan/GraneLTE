<?php

namespace App\Actions\Inventory;

use App\Models\Inventory\RawMaterialBatch;
use App\Models\Inventory\RawMaterialDocument;
use App\Enums\Inventory\RawMaterialDocument\RawMaterialDocumentStatus;
use App\Enums\Inventory\RawMaterialDocument\RawMaterialDocumentType;
use App\Enums\Inventory\RawMaterialMovement\MovementType;
use App\Models\Inventory\RawMaterialMovement;
use Illuminate\Support\Facades\DB;

class ExecuteRawMaterialDocument
{
    public static function execute(RawMaterialDocument $document): void
    {
        match ($document->type) {
            RawMaterialDocumentType::RECEIPT    => self::receipt($document),
            RawMaterialDocumentType::ISSUE      => self::issue($document),
            RawMaterialDocumentType::TRANSFER   => self::transfer($document),
            RawMaterialDocumentType::ADJUSTMENT => self::adjustment($document)
        };
    }

    private static function receipt(RawMaterialDocument $document): void
    {
        DB::transaction(function () use ($document) {
            $receipt = $document->receipt;
            $lines   = $document->receiptLines;

            foreach ($lines as $line) {
                $batch = RawMaterialBatch::create([
                    'batch_code'            => $line->material->generateBatchCodeUnique(),
                    'external_batch_code'   => $line->external_batch_code,
                    'received_quantity'     => $line->received_quantity,
                    'received_total_cost'   => $line->received_total_cost,
                    'received_unit_cost'    => $line->received_unit_cost,
                    'expiration_date'       => $line->expiration_date,
                    'material_id'           => $line->material_id,

                    'received_at'           => $document->effective_at,
                    'supplier_id'           => $receipt->supplier_id,
                ]);

                $movement = RawMaterialMovement::create([
                    'type'          => MovementType::RECEIPT,
                    'quantity'      => $line->received_quantity,
                    'effective_at'  => $document->effective_at,
                    'batch_id'      => $batch->id,
                    'warehouse_id'  => $line->warehouse_id,
                    'document_id'   => $document->id,
                ]);

                $movement->execute();
            }
        });
    }

    private static function issue(RawMaterialDocument $document): void {}

    private static function transfer(RawMaterialDocument $document): void {}

    private static function adjustment(RawMaterialDocument $document): void {}
}
