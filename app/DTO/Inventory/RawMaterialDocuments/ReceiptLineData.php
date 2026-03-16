<?php

namespace App\DTO\Inventory\RawMaterialDocuments;

use App\Models\Inventory\RawMaterial;
use App\Models\Inventory\Warehouse;

/**
 * DTO de solo lectura para construir una linea de entrada.
 * Los campos de presentacion (nombres, simbolos) viajan junto
 * con los datos de formulario para evitar consultas adicionales
 * durante el ciclo de vida de Livewire.
 */
final readonly class ReceiptLineData
{
    public function __construct(
        public int     $material_id,
        public string  $raw_material_name,
        public string  $unit_name,
        public string  $unit_symbol,
        public int     $warehouse_id,
        public string  $warehouse_name,
        public ?string $external_batch_code = null,
        public string  $received_quantity   = '0',
        public string  $received_unit_cost  = '0.00',
        public string  $received_total_cost = '0.00',
        public ?string $expiration_date     = null,
    ) {}

    public static function fromModels(RawMaterial $material, Warehouse $warehouse, int $truncateText = 0): self
    {
        if ($truncateText > 0) {
            return new self(
                material_id:        $material->id,
                raw_material_name:  $material->truncateText('name', $truncateText),
                unit_name:          $material->unit->name,
                unit_symbol:        $material->unit->symbol,
                warehouse_id:       $warehouse->id,
                warehouse_name:     $warehouse->truncateText('name', $truncateText),
            );
        }

        return new self(
            material_id:        $material->id,
            raw_material_name:  $material->name,
            unit_name:          $material->unit->name,
            unit_symbol:        $material->unit->symbol,
            warehouse_id:       $warehouse->id,
            warehouse_name:     $warehouse->name,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'material_id'         => $this->material_id,
            'raw_material_name'   => $this->raw_material_name,
            'unit_name'           => $this->unit_name,
            'unit_symbol'         => $this->unit_symbol,
            'warehouse_id'        => $this->warehouse_id,
            'warehouse_name'      => $this->warehouse_name,
            'external_batch_code' => $this->external_batch_code,
            'received_quantity'   => $this->received_quantity,
            'received_unit_cost'  => $this->received_unit_cost,
            'received_total_cost' => $this->received_total_cost,
            'expiration_date'     => $this->expiration_date,
        ];
    }
}
