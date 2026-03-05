<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property numeric $quantity
 * @property int $stock_origin_id
 * @property int $warehouse_dest_id
 * @property int $document_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Inventory\RawMaterialDocument $document
 * @property-read \App\Models\Inventory\RawMaterialStock $originStock
 * @property-read \App\Models\Inventory\Warehouse $warehouseDest
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialTransferLine newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialTransferLine newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialTransferLine query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialTransferLine whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialTransferLine whereDocumentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialTransferLine whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialTransferLine whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialTransferLine whereStockOriginId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialTransferLine whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialTransferLine whereWarehouseDestId($value)
 * @mixin \Eloquent
 */
class RawMaterialTransferLine extends Model
{
    use HasFactory;

    protected $table = 'raw_material_transfer_lines';

    protected $fillable = [
        'quantity',
        'stock_origin_id',
        'warehouse_dest_id',
        'document_id',
    ];

    protected $casts = [
        'quantity' => 'decimal:3',
    ];

    public function originStock(): BelongsTo
    {
        return $this->belongsTo(RawMaterialStock::class, 'stock_origin_id');
    }

    public function warehouseDest(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_dest_id');
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(RawMaterialDocument::class, 'document_id');
    }
}
