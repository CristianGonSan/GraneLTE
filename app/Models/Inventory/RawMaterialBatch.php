<?php

namespace App\Models\Inventory;

use App\Traits\Models\HasActiveState;
use App\Traits\Models\TruncateText;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;



/**
 * @property int $id
 * @property string $batch_code
 * @property string|null $external_batch_code
 * @property numeric $received_quantity
 * @property numeric $received_total_cost
 * @property numeric $received_unit_cost
 * @property \Illuminate\Support\Carbon $received_at
 * @property \Illuminate\Support\Carbon|null $expiration_date
 * @property int $raw_material_id
 * @property int $supplier_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Inventory\RawMaterial|null $material
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Inventory\RawMaterialStock> $stocks
 * @property-read int|null $stocks_count
 * @property-read \App\Models\Inventory\Supplier $supplier
 * @method static Builder<static>|RawMaterialBatch active()
 * @method static Builder<static>|RawMaterialBatch fEFO()
 * @method static Builder<static>|RawMaterialBatch fIFO()
 * @method static Builder<static>|RawMaterialBatch inactive()
 * @method static Builder<static>|RawMaterialBatch lIFO()
 * @method static Builder<static>|RawMaterialBatch newModelQuery()
 * @method static Builder<static>|RawMaterialBatch newQuery()
 * @method static Builder<static>|RawMaterialBatch query()
 * @method static Builder<static>|RawMaterialBatch whereBatchCode($value)
 * @method static Builder<static>|RawMaterialBatch whereCreatedAt($value)
 * @method static Builder<static>|RawMaterialBatch whereExpirationDate($value)
 * @method static Builder<static>|RawMaterialBatch whereExternalBatchCode($value)
 * @method static Builder<static>|RawMaterialBatch whereId($value)
 * @method static Builder<static>|RawMaterialBatch whereRawMaterialId($value)
 * @method static Builder<static>|RawMaterialBatch whereReceivedAt($value)
 * @method static Builder<static>|RawMaterialBatch whereReceivedQuantity($value)
 * @method static Builder<static>|RawMaterialBatch whereReceivedTotalCost($value)
 * @method static Builder<static>|RawMaterialBatch whereReceivedUnitCost($value)
 * @method static Builder<static>|RawMaterialBatch whereSupplierId($value)
 * @method static Builder<static>|RawMaterialBatch whereUpdatedAt($value)
 * @mixin \Eloquent
 * @mixin IdeHelperRawMaterialBatch
 */
class RawMaterialBatch extends Model
{
    use HasFactory, HasActiveState, TruncateText;

    protected $table = 'raw_material_batches';

    protected $fillable = [
        'batch_code',
        'external_batch_code',
        'received_quantity',
        'received_total_cost',
        'received_unit_cost',
        'received_at',
        'expiration_date',
        'current_quantity',
        'material_id',
        'supplier_id',
    ];

    protected $casts = [
        'received_quantity'     => 'decimal:3',
        'current_quantity'      => 'decimal:3',
        'received_total_cost'   => 'decimal:2',
        'received_unit_cost'    => 'decimal:2',
        'received_at'           => 'date',
        'expiration_date'       => 'datetime',
    ];

    public function currentCost(): string
    {
        return bcmul($this->current_quantity, $this->received_unit_cost, 2);
    }

    public function code(): string
    {
        return $this->external_batch_code ?? $this->batch_code;
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(RawMaterial::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(RawMaterialStock::class, 'batch_id');
    }

    public function movements(): HasMany
    {
        return $this->hasMany(RawMaterialMovement::class, 'batch_id');
    }

    public function scopeFIFO($query): Builder
    {
        return $query->orderBy('received_at', 'asc');
    }

    public function scopeLIFO($query): Builder
    {
        return $query->orderBy('received_at', 'desc');
    }

    public function scopeFEFO($query): Builder
    {
        return $query->orderByRaw('expiration_date IS NULL')
            ->orderBy('expiration_date', 'asc');
    }
}
