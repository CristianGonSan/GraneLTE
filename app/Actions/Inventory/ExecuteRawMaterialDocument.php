<?php

namespace App\Actions\Inventory;

use App\Models\Inventory\RawMaterialBatch;
use App\Models\Inventory\RawMaterialDocument;
use App\Enums\Inventory\RawMaterialDocument\RawMaterialDocumentType as DocumentType;
use App\Enums\Inventory\RawMaterialMovement\RawMaterialMovementType as MovementType;
use DomainException;
use Illuminate\Support\Facades\DB;

class ExecuteRawMaterialDocument
{
    public static function execute(RawMaterialDocument $document): void
    {
        match ($document->type) {
            DocumentType::RECEIPT    => self::receipt($document),
            DocumentType::ISSUE      => self::issue($document),
            DocumentType::TRANSFER   => self::transfer($document),
            DocumentType::ADJUSTMENT => self::adjustment($document)
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

                $movement = $document->movements()->create([
                    'type'          => MovementType::RECEIPT,
                    'quantity'      => $line->received_quantity,
                    'effective_at'  => $document->effective_at,
                    'batch_id'      => $batch->id,
                    'warehouse_id'  => $line->warehouse_id
                ]);

                $movement->execute();
            }
        });
    }

    private static function issue(RawMaterialDocument $document): void
    {
        DB::transaction(function () use ($document) {
            $lines = $document->issueLines;

            foreach ($lines as $line) {
                $stock = $line->stock;

                if ($line->quantity > $stock->current_quantity) {
                    throw new DomainException('Stock insuficiente');
                }

                $movement = $document->movements()->create([
                    'type'          => MovementType::ISSUE,
                    'quantity'      => $line->quantity,
                    'effective_at'  => $document->effective_at,
                    'batch_id'      => $stock->batch_id,
                    'warehouse_id'  => $stock->warehouse_id
                ]);

                $movement->execute();
            }
        });
    }

    private static function transfer(RawMaterialDocument $document): void
    {
        DB::transaction(function () use ($document) {
            $lines = $document->transferLines;

            foreach ($lines as $line) {
                $stock = $line->originStock;

                if ($line->quantity > $stock->current_quantity) {
                    throw new DomainException('Stock insuficiente');
                }

                //Sale material del alamcen de origen
                $movementOut = $document->movements()->create([
                    'type'          => MovementType::TRANSFER_OUT,
                    'quantity'      => $line->quantity,
                    'effective_at'  => $document->effective_at,
                    'batch_id'      => $stock->batch_id,
                    'warehouse_id'  => $stock->warehouse_id
                ]);

                //Entra material en el almacen de destino
                $movementIn = $document->movements()->create([
                    'type'          => MovementType::TRANSFER_IN,
                    'quantity'      => $line->quantity,
                    'effective_at'  => $document->effective_at,
                    'batch_id'      => $stock->batch_id,
                    'warehouse_id'  => $line->warehouse_dest_id
                ]);

                $movementOut->execute();
                $movementIn->execute();
            }
        });
    }

    private static function adjustment(RawMaterialDocument $document): void
    {
        DB::transaction(function () use ($document) {
            $lines = $document->adjustmentLines;

            foreach ($lines as $line) {
                $stock = $line->stock;

                $difference     = $line->difference_quantity;
                $absDifference  = self::bcabs($difference, 3);

                if (bccomp($difference, '0', 3) < 0) {
                    $type = MovementType::ADJUSTMENT_OUT;
                    $result = bcsub($stock->current_quantity, $absDifference, 3);
                    if (bccomp($result, '0', 3) < 0) {
                        throw new DomainException('El Stock resultante es negativo');
                    }
                } else {
                    $type = MovementType::ADJUSTMENT_IN;
                }

                $movement = $document->movements()->create([
                    'type'         => $type,
                    'quantity'     => $absDifference,
                    'effective_at' => $document->effective_at,
                    'batch_id'     => $stock->batch_id,
                    'warehouse_id' => $stock->warehouse_id
                ]);

                $movement->execute();
            }
        });
    }

    private static function bcabs(string $number, int $scale = 3): string
    {
        return bccomp($number, '0', $scale) < 0
            ? ltrim($number, '-')
            : $number;
    }
}
