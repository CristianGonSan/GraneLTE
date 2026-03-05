<?php

namespace App\Models\Inventory;

use App\Traits\Models\TruncateText;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\DB;

/**
 * @property int $id
 * @property string $batch_code
 * @property string|null $external_batch_code
 * @property numeric $received_quantity
 * @property numeric $received_total_cost
 * @property numeric $received_unit_cost
 * @property numeric $current_quantity
 * @property \Illuminate\Support\Carbon $received_at
 * @property \Illuminate\Support\Carbon|null $expiration_date
 * @property int $material_id
 * @property int $supplier_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $code
 * @property-read mixed $current_cost
 * @property-read \App\Models\Inventory\RawMaterial $material
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Inventory\RawMaterialMovement> $movements
 * @property-read int|null $movements_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Inventory\RawMaterialStock> $stocks
 * @property-read int|null $stocks_count
 * @property-read \App\Models\Inventory\Supplier $supplier
 * @method static Builder<static>|RawMaterialBatch empty()
 * @method static Builder<static>|RawMaterialBatch expired()
 * @method static Builder<static>|RawMaterialBatch expiring(int $days = 30, bool $includeExpired = true)
 * @method static Builder<static>|RawMaterialBatch fEFO()
 * @method static Builder<static>|RawMaterialBatch fIFO()
 * @method static Builder<static>|RawMaterialBatch lIFO()
 * @method static Builder<static>|RawMaterialBatch newModelQuery()
 * @method static Builder<static>|RawMaterialBatch newQuery()
 * @method static Builder<static>|RawMaterialBatch noEmpty()
 * @method static Builder<static>|RawMaterialBatch noExpired()
 * @method static Builder<static>|RawMaterialBatch query()
 * @method static Builder<static>|RawMaterialBatch whereBatchCode($value)
 * @method static Builder<static>|RawMaterialBatch whereCreatedAt($value)
 * @method static Builder<static>|RawMaterialBatch whereCurrentQuantity($value)
 * @method static Builder<static>|RawMaterialBatch whereExpirationDate($value)
 * @method static Builder<static>|RawMaterialBatch whereExternalBatchCode($value)
 * @method static Builder<static>|RawMaterialBatch whereId($value)
 * @method static Builder<static>|RawMaterialBatch whereMaterialId($value)
 * @method static Builder<static>|RawMaterialBatch whereReceivedAt($value)
 * @method static Builder<static>|RawMaterialBatch whereReceivedQuantity($value)
 * @method static Builder<static>|RawMaterialBatch whereReceivedTotalCost($value)
 * @method static Builder<static>|RawMaterialBatch whereReceivedUnitCost($value)
 * @method static Builder<static>|RawMaterialBatch whereSupplierId($value)
 * @method static Builder<static>|RawMaterialBatch whereUpdatedAt($value)
 * @method static Builder<static>|RawMaterialBatch withExpiration()
 * @method static Builder<static>|RawMaterialBatch withoutExpiration()
 * @mixin \Eloquent
 */
class RawMaterialBatch extends Model
{
    use HasFactory, TruncateText;

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

    protected $appends = [
        'current_cost',
        'code',
    ];

    public function isExpired(): bool
    {
        if (!$this->expiration_date) {
            return false;
        }

        return $this->expiration_date->isPast();
    }

    public function currentCost(): Attribute
    {
        return Attribute::make(
            fn() => bcmul(
                $this->current_quantity,
                $this->received_unit_cost,
                2
            )
        );
    }

    public function code(): Attribute
    {
        return Attribute::make(
            fn() => $this->external_batch_code ?? $this->batch_code
        );
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

    public function scopeFIFO(Builder $query): Builder
    {
        return $query->orderBy('received_at', 'asc');
    }

    public function scopeLIFO(Builder $query): Builder
    {
        return $query->orderBy('received_at', 'desc');
    }

    public function scopeFEFO(Builder $query): Builder
    {
        return $query->orderByRaw('expiration_date IS NULL')
            ->orderBy('expiration_date', 'asc');
    }

    public function scopeNoEmpty(Builder $query): Builder
    {
        return $query->where('current_quantity', '>', 0);
    }

    public function scopeEmpty(Builder $query): Builder
    {
        return $query->where('current_quantity', '<=', 0);
    }

    /**
     * @param Builder<RawMaterialBatch> $query
     * @return Builder<RawMaterialBatch>
     */
    public function scopeExpiring(Builder $query, int $days = 30, bool $includeExpired = true): Builder
    {
        $limitDate = now()->addDays($days);

        return $query
            ->noEmpty()
            ->whereNotNull('expiration_date')
            ->when(
                $includeExpired,
                fn($q) => $q->where('expiration_date', '<=', $limitDate),
                fn($q) => $q->whereBetween('expiration_date', [now(), $limitDate])
            )
            ->orderBy('expiration_date', 'asc');
    }

    public function scopeExpired(Builder $query): Builder
    {
        return $query->whereNotNull('expiration_date')
            ->where('expiration_date', '<=', now());
    }

    public function scopeNoExpired(Builder $query): Builder
    {
        return $query->where(function ($q) {
            $q->whereNull('expiration_date')
                ->orWhere('expiration_date', '>', now());
        });
    }

    public function scopeWithExpiration(Builder $query): Builder
    {
        return $query->whereNotNull('expiration_date');
    }

    public function scopeWithoutExpiration(Builder $query): Builder
    {
        return $query->whereNull('expiration_date');
    }
}
