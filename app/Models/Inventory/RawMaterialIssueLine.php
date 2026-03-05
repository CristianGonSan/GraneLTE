<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

/**
 * @property int $id
 * @property numeric $quantity
 * @property int $stock_id
 * @property int $document_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Inventory\RawMaterialBatch|null $batch
 * @property-read \App\Models\Inventory\RawMaterialDocument $document
 * @property-read \App\Models\Inventory\RawMaterialStock $stock
 * @property-read \App\Models\Inventory\Warehouse|null $warehouse
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialIssueLine newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialIssueLine newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialIssueLine query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialIssueLine whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialIssueLine whereDocumentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialIssueLine whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialIssueLine whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialIssueLine whereStockId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialIssueLine whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class RawMaterialIssueLine extends Model
{
    use HasFactory;

    protected $table = 'raw_material_issue_lines';

    protected $fillable = [
        'quantity',
        'stock_id',
        'document_id',
    ];

    protected $casts = [
        'quantity' => 'decimal:3',
    ];

    public function totalCost(): string
    {
        return bcmul($this->quantity, $this->batch->received_unit_cost, 3);
    }

    public function stock(): BelongsTo
    {
        return $this->belongsTo(RawMaterialStock::class, 'stock_id');
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(RawMaterialDocument::class, 'document_id');
    }

    public function batch(): HasOneThrough
    {
        return $this->hasOneThrough(
            RawMaterialBatch::class,
            RawMaterialStock::class,
            'id',
            'id',
            'stock_id',
            'batch_id'
        );
    }

    public function warehouse(): HasOneThrough
    {
        return $this->hasOneThrough(
            Warehouse::class,
            RawMaterialStock::class,
            'id',
            'id',
            'stock_id',
            'warehouse_id'
        );
    }
}
