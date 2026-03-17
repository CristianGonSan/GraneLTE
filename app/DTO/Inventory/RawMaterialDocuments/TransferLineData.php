<?php

namespace App\DTO\Inventory\RawMaterialDocuments;

use App\Models\Inventory\RawMaterialStock;

/**
 * DTO de solo lectura para construir la linea unica de una transferencia.
 * Los campos de presentacion viajan junto con los datos de formulario
 * para evitar consultas adicionales durante el ciclo de vida de Livewire.
 */
final readonly class TransferLineData
{
    public function __construct(
        public int     $stock_origin_id,
        public string  $raw_material_name,
        public string  $unit_name,
        public string  $unit_symbol,
        public string  $warehouse_name,
        public string  $batch_code,
        public string  $unit_cost,
        public string  $current_quantity,
        public ?string $quantity         = null,
        public string  $total_cost       = '0.00',
        public bool    $invalid_quantity = false,
    ) {}

    public static function fromStock(RawMaterialStock $stock, string $textSize = 'medium'): self
    {
        $batch     = $stock->batch;
        $material  = $batch->material;
        $warehouse = $stock->warehouse;

        return new self(
            stock_origin_id: $stock->id,
            raw_material_name: $material->truncateText('name', $textSize),
            unit_name: $material->unit->name,
            unit_symbol: $material->unit->symbol,
            warehouse_name: $warehouse->truncateText('name', $textSize),
            batch_code: $batch->code,
            unit_cost: $batch->received_unit_cost,
            current_quantity: $stock->current_quantity,
        );

        return new self(
            stock_origin_id: $stock->id,
            raw_material_name: $material->name,
            unit_name: $material->unit->name,
            unit_symbol: $material->unit->symbol,
            warehouse_name: $warehouse->name,
            batch_code: $batch->code,
            unit_cost: $batch->received_unit_cost,
            current_quantity: $stock->current_quantity,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'stock_origin_id'   => $this->stock_origin_id,
            'raw_material_name' => $this->raw_material_name,
            'unit_name'         => $this->unit_name,
            'unit_symbol'       => $this->unit_symbol,
            'warehouse_name'    => $this->warehouse_name,
            'batch_code'        => $this->batch_code,
            'unit_cost'         => $this->unit_cost,
            'current_quantity'  => $this->current_quantity,
            'quantity'          => $this->quantity,
            'total_cost'        => $this->total_cost,
            'invalid_quantity'  => $this->invalid_quantity,
        ];
    }
}
