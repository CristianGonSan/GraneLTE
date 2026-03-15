<?php

namespace App\Actions\Inventory;

use App\Models\Inventory\RawMaterialBatch;
use App\Models\Inventory\RawMaterialDocument;
use App\Models\Inventory\RawMaterialStock;
use App\Enums\Inventory\RawMaterialDocument\RawMaterialDocumentType as DocumentType;
use App\Enums\Inventory\RawMaterialMovement\RawMaterialMovementType as MovementType;
use DomainException;
use Illuminate\Support\Facades\DB;


class ExecuteRawMaterialDocument
{
    public static function execute(RawMaterialDocument $document): void
    {
        DB::transaction(function () use ($document): void {
            match ($document->type) {
                DocumentType::RECEIPT    => self::receipt($document),
                DocumentType::ISSUE      => self::issue($document),
                DocumentType::TRANSFER   => self::transfer($document),
                DocumentType::ADJUSTMENT => self::adjustment($document),
            };
        });
    }

    private static function receipt(RawMaterialDocument $document): void
    {
        $lines = $document->receiptLines;

        foreach ($lines as $line) {
            $batch = RawMaterialBatch::create([
                'batch_code'          => $line->material->generateBatchCodeUnique(),
                'external_batch_code' => $line->external_batch_code,
                'received_quantity'   => $line->received_quantity,
                'received_total_cost' => $line->received_total_cost,
                'received_unit_cost'  => $line->received_unit_cost,
                'expiration_date'     => $line->expiration_date,
                'material_id'         => $line->material_id,
                'received_at'         => $document->effective_at,
                'supplier_id'         => $document->receipt->supplier_id,
                'current_quantity'    => '0',
            ]);

            $movement = $document->movements()->create([
                'type'         => MovementType::RECEIPT,
                'quantity'     => $line->received_quantity,
                'effective_at' => $document->effective_at,
                'batch_id'     => $batch->id,
                'warehouse_id' => $line->warehouse_id,
            ]);

            // El stock aun no existe; ExecuteRawMaterialMovement lo creara.
            $movement->execute();
        }
    }

    private static function issue(RawMaterialDocument $document): void
    {
        $lines = $document->issueLines;

        foreach ($lines as $line) {
            $stock = self::lockStock($line->stock_id);

            self::assertSufficientStock(
                available: $stock->current_quantity,
                required: $line->quantity,
                context: "línea de salida [{$line->id}]"
            );

            $movement = $document->movements()->create([
                'type'         => MovementType::ISSUE,
                'quantity'     => $line->quantity,
                'effective_at' => $document->effective_at,
                'batch_id'     => $stock->batch_id,
                'warehouse_id' => $stock->warehouse_id,
            ]);

            $movement->execute($stock);
        }
    }

    private static function transfer(RawMaterialDocument $document): void
    {
        $lines = $document->transferLines;

        foreach ($lines as $line) {
            $originStock = self::lockStock($line->stock_origin_id);

            self::assertSufficientStock(
                available: $originStock->current_quantity,
                required: $line->quantity,
                context: "línea de transferencia [{$line->id}]"
            );

            $movementOut = $document->movements()->create([
                'type'         => MovementType::TRANSFER_OUT,
                'quantity'     => $line->quantity,
                'effective_at' => $document->effective_at,
                'batch_id'     => $originStock->batch_id,
                'warehouse_id' => $originStock->warehouse_id,
            ]);

            $movementIn = $document->movements()->create([
                'type'         => MovementType::TRANSFER_IN,
                'quantity'     => $line->quantity,
                'effective_at' => $document->effective_at,
                'batch_id'     => $originStock->batch_id,
                'warehouse_id' => $line->warehouse_dest_id,
            ]);

            // El stock de origen ya esta bloqueado; el de destino puede no existir.
            $movementOut->execute($originStock);
            $movementIn->execute();
        }
    }

    private static function adjustment(RawMaterialDocument $document): void
    {
        $lines = $document->adjustmentLines;

        foreach ($lines as $line) {
            $stock      = self::lockStock($line->stock_id);
            $difference = $line->difference_quantity;
            $absQty     = self::bcabs($difference, 3);

            if (bccomp($difference, '0', 3) < 0) {
                $type   = MovementType::ADJUSTMENT_OUT;
                $result = bcsub($stock->current_quantity, $absQty, 3);

                if (bccomp($result, '0', 3) < 0) {
                    throw new DomainException(
                        "El ajuste dejaría stock negativo en la línea [{$line->id}]. "
                            . "Disponible: {$stock->current_quantity}, ajuste: -{$absQty}."
                    );
                }
            } else {
                $type = MovementType::ADJUSTMENT_IN;
            }

            $movement = $document->movements()->create([
                'type'         => $type,
                'quantity'     => $absQty,
                'effective_at' => $document->effective_at,
                'batch_id'     => $stock->batch_id,
                'warehouse_id' => $stock->warehouse_id,
            ]);

            ExecuteRawMaterialMovement::execute($movement, $stock);
        }
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    /**
     * Carga un RawMaterialStock con bloqueo pesimista (SELECT ... FOR UPDATE).
     * Debe llamarse siempre dentro de una transaccion activa.
     */
    private static function lockStock(int $stockId): RawMaterialStock
    {
        $stock = RawMaterialStock::lockForUpdate()->find($stockId);

        if (!$stock instanceof RawMaterialStock) {
            throw new DomainException("El stock [{$stockId}] no existe.");
        }

        return $stock;
    }

    /**
     * Lanza una excepcion si la cantidad disponible es menor a la requerida.
     */
    private static function assertSufficientStock(
        string $available,
        string $required,
        string $context
    ): void {
        if (bccomp($required, $available, 3) > 0) {
            throw new DomainException(
                "Stock insuficiente en {$context}. "
                    . "Disponible: {$available}, requerido: {$required}."
            );
        }
    }

    /**
     * Devuelve el valor absoluto de un numero representado como cadena de texto.
     */
    private static function bcabs(string $number, int $scale = 3): string
    {
        return bccomp($number, '0', $scale) < 0
            ? ltrim($number, '-')
            : $number;
    }
}
