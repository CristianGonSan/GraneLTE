<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $quantity
 * @property int $batch_id
 * @property int $warehouse_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Inventory\RawMaterialBatch $batch
 * @property-read \App\Models\Inventory\Warehouse $warehouse
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialStock newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialStock newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialStock query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialStock whereBatchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialStock whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialStock whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialStock whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialStock whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialStock whereWarehouseId($value)
 * @mixin \Eloquent
 * @mixin IdeHelperRawMaterialStock
 */
class RawMaterialStock extends Model
{
    use HasFactory;

    protected $table = 'raw_material_stocks';

    protected $fillable = [
        'current_quantity',
        'batch_id',
        'warehouse_id',
    ];

    protected $casts = [
        'stock' => 'decimal:3',
    ];

    public function batch(): BelongsTo
    {
        return $this->belongsTo(RawMaterialBatch::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }
}
