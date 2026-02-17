<?php

namespace App\Actions\Inventory;

use App\Enums\Inventory\RawMaterialMovement\MovementType;
use App\Models\Inventory\RawMaterialMovement;
use App\Models\Inventory\RawMaterialStock;

class ExecuteRawMaterialMovement
{
    public static function execute(RawMaterialMovement $movement): void
    {
        $quantityMoved  = $movement->quantity;

        $batch      = $movement->batch;
        $material   = $batch->material;
        $stock      = $movement->stock;

        if (!$stock) {
            $stock = RawMaterialStock::create([
                'current_quantity'  => '0',
                'batch_id'          => $movement->batch_id,
                'warehouse_id'      => $movement->warehouse_id,
            ]);
        }

        if ($movement->type->isIncrement()) {
            $stock->increment('current_quantity', $quantityMoved);
            $material->increment('current_quantity', $quantityMoved);
            $batch->increment('current_quantity', $quantityMoved);
        } else {
            $stock->decrement('current_quantity', $quantityMoved);
            $material->increment('current_quantity', $quantityMoved);
            $batch->decrement('current_quantity', $quantityMoved);
        }
    }
}
