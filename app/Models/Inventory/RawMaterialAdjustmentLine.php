<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property numeric $theoretical_quantity
 * @property numeric $counted_quantity
 * @property numeric $difference_quantity
 * @property int $stock_id
 * @property int $document_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Inventory\RawMaterialDocument $document
 * @property-read \App\Models\Inventory\RawMaterialStock $stock
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialAdjustmentLine newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialAdjustmentLine newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialAdjustmentLine query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialAdjustmentLine whereCountedQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialAdjustmentLine whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialAdjustmentLine whereDifferenceQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialAdjustmentLine whereDocumentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialAdjustmentLine whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialAdjustmentLine whereStockId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialAdjustmentLine whereTheoreticalQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialAdjustmentLine whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class RawMaterialAdjustmentLine extends Model
{
    use HasFactory;

    protected $table = 'raw_material_adjustment_lines';

    protected $fillable = [
        'theoretical_quantity',
        'counted_quantity',
        'difference_quantity',
        'stock_id',
        'document_id',
    ];

    protected $casts = [
        'theoretical_quantity' => 'decimal:3',
        'counted_quantity'     => 'decimal:3',
        'difference_quantity'  => 'decimal:3',
    ];

    public function stock(): BelongsTo
    {
        return $this->belongsTo(RawMaterialStock::class, 'stock_id');
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(RawMaterialDocument::class, 'document_id');
    }
}
