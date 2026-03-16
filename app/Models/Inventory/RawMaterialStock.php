<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property int $id
 * @property string $current_quantity
 * @property int $batch_id
 * @property int $warehouse_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Inventory\RawMaterialBatch $batch
 * @property-read mixed $current_cost
 * @property-read \App\Models\Inventory\Warehouse $warehouse
 * @method static Builder<static>|RawMaterialStock available()
 * @method static Builder<static>|RawMaterialStock newModelQuery()
 * @method static Builder<static>|RawMaterialStock newQuery()
 * @method static Builder<static>|RawMaterialStock query()
 * @method static Builder<static>|RawMaterialStock whereBatchId($value)
 * @method static Builder<static>|RawMaterialStock whereCreatedAt($value)
 * @method static Builder<static>|RawMaterialStock whereCurrentQuantity($value)
 * @method static Builder<static>|RawMaterialStock whereId($value)
 * @method static Builder<static>|RawMaterialStock whereUpdatedAt($value)
 * @method static Builder<static>|RawMaterialStock whereWarehouseId($value)
 * @mixin \Eloquent
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
        'current_quantity' => 'decimal:3',
    ];

    protected $appends = [
        'current_cost',
    ];

    protected function currentCost(): Attribute
    {
        return Attribute::make(
            fn() => bcmul(
                $this->current_quantity,
                $this->batch->received_unit_cost,
                2
            )
        );
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(RawMaterialBatch::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('current_quantity', '>', 0);
    }
}
