<?php

namespace App\Models\Inventory;

use App\Actions\Inventory\ExecuteRawMaterialMovement;
use App\Enums\Inventory\RawMaterialMovement\MovementType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property MovementType $type
 * @property numeric $quantity
 * @property \Illuminate\Support\Carbon $effective_at
 * @property int $batch_id
 * @property int $warehouse_id
 * @property int $document_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Inventory\RawMaterialBatch $batch
 * @property-read \App\Models\Inventory\RawMaterialDocument $document
 * @property-read \App\Models\Inventory\Warehouse $warehouse
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialMovement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialMovement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialMovement query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialMovement whereBatchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialMovement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialMovement whereDocumentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialMovement whereEffectiveAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialMovement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialMovement whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialMovement whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialMovement whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialMovement whereWarehouseId($value)
 * @mixin \Eloquent
 * @mixin IdeHelperRawMaterialMovement
 */
class RawMaterialMovement extends Model
{
    use HasFactory;

    protected $table = 'raw_material_movements';

    protected $fillable = [
        'type',
        'quantity',
        'effective_at',
        'batch_id',
        'warehouse_id',
        'document_id',
    ];

    protected $casts = [
        'type'         => MovementType::class,
        'quantity'     => 'decimal:3',
        'effective_at' => 'datetime',
    ];

    public function execute(): void
    {
        ExecuteRawMaterialMovement::execute($this);
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(RawMaterialBatch::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(RawMaterialDocument::class);
    }

    public function stock(): HasOne
    {
        return $this->hasOne(RawMaterialStock::class, 'batch_id', 'batch_id')
            ->where('raw_material_stocks.warehouse_id', $this->warehouse_id);
    }
}
