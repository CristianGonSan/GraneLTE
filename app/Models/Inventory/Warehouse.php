<?php

namespace App\Models\Inventory;

use App\Traits\Models\HasActiveState;
use App\Traits\Models\TruncateText;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\DB;

/**
 * @property int $id
 * @property string $name
 * @property string|null $location
 * @property string|null $description
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $current_cost
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Inventory\RawMaterialBatch> $rawMaterialBatches
 * @property-read int|null $raw_material_batches_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Inventory\RawMaterialStock> $rawMaterialStocks
 * @property-read int|null $raw_material_stocks_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse active()
 * @method static \Database\Factories\Inventory\WarehouseFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse inactive()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Warehouse extends Model
{
    use HasFactory, HasActiveState, TruncateText;

    protected $table = 'warehouses';

    protected $fillable = [
        'name',
        'location',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'bool',
    ];

    protected $appends = [
        'current_cost'
    ];

    public function isInUse(): bool
    {
        return $this->rawMaterialStocks()->exists();
    }

    public function currentCost(): Attribute
    {
        return Attribute::make(
            fn() => $this->rawMaterialStocks()
                ->join('raw_material_batches as batches', 'batches.id', '=', 'raw_material_stocks.batch_id')
                ->where('raw_material_stocks.current_quantity', '>', 0)
                ->sum(DB::raw('raw_material_stocks.current_quantity * batches.received_unit_cost'))
        );
    }

    public function rawMaterialStocks(): HasMany
    {
        return $this->hasMany(RawMaterialStock::class, 'warehouse_id');
    }

    public function rawMaterialBatches(): HasManyThrough
    {
        return $this->hasManyThrough(
            RawMaterialBatch::class,   // Modelo final
            RawMaterialStock::class,   // Modelo intermedio
            'warehouse_id',            // FK en raw_material_stocks hacia warehouses
            'id',                      // PK en raw_material_batches
            'id',                      // PK en warehouses
            'batch_id'                 // FK en raw_material_stocks hacia batches
        );
    }
}
