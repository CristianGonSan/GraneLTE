<?php

namespace App\Actions\Inventory;

use App\Models\Inventory\RawMaterial;
use App\Models\Inventory\RawMaterialBatch;
use App\Models\Inventory\RawMaterialMovement;
use App\Models\Inventory\RawMaterialStock;
use DomainException;

class ExecuteRawMaterialMovement
{
    public static function execute(RawMaterialMovement $movement, ?RawMaterialStock $originStock = null): void
    {
        $quantity = $movement->quantity;

        /** @var RawMaterialBatch $batch */
        $batch = $movement->batch()->lockForUpdate()->firstOrFail();

        /** @var RawMaterial $material */
        $material = $batch->material()->lockForUpdate()->firstOrFail();

        $originStock ??= self::resolveStock($movement);

        if ($movement->type->isIncrement()) {
            $newStockQty    = bcadd($originStock->current_quantity, $quantity, 3);
            $newBatchQty    = bcadd($batch->current_quantity,       $quantity, 3);
            $newMaterialQty = bcadd($material->current_quantity,    $quantity, 3);
        } else {
            $newStockQty = bcsub($originStock->current_quantity, $quantity, 3);

            if (bccomp($newStockQty, '0', 3) < 0) {
                throw new DomainException(
                    "Stock insuficiente para el lote [{$batch->batch_code}] "
                        . "en el almacén [{$movement->warehouse_id}]. "
                        . "Disponible: {$originStock->current_quantity}, requerido: {$quantity}."
                );
            }

            $newBatchQty    = bcsub($batch->current_quantity,    $quantity, 3);
            $newMaterialQty = bcsub($material->current_quantity, $quantity, 3);
        }

        $originStock->update(['current_quantity' => $newStockQty]);
        $batch->update(['current_quantity'    => $newBatchQty]);
        $material->update(['current_quantity' => $newMaterialQty]);
    }

    /**
     * Obtiene el stock correspondiente al movimiento con un bloqueo pesimista
     * (SELECT ... FOR UPDATE). Debe llamarse dentro de una transaccion activa.
     * Si no existe, lo crea con cantidad cero.
     */
    private static function resolveStock(RawMaterialMovement $movement): RawMaterialStock
    {
        $stock = RawMaterialStock::where('batch_id',    $movement->batch_id)
            ->where('warehouse_id', $movement->warehouse_id)
            ->lockForUpdate()
            ->first();

        if (!$stock instanceof RawMaterialStock) {
            $stock = RawMaterialStock::create([
                'current_quantity' => '0',
                'batch_id'         => $movement->batch_id,
                'warehouse_id'     => $movement->warehouse_id,
            ]);
        }

        return $stock;
    }
}
