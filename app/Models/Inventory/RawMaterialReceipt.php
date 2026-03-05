<?php

namespace App\Models\Inventory;

use App\Models\Inventory\Supplier;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $supplier_id
 * @property int $document_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Inventory\RawMaterialDocument $document
 * @property-read Supplier $supplier
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialReceipt newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialReceipt newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialReceipt query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialReceipt whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialReceipt whereDocumentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialReceipt whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialReceipt whereSupplierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialReceipt whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class RawMaterialReceipt extends Model
{
    use HasFactory;

    protected $table = 'raw_material_receipts';

    protected $fillable = [
        'supplier_id',
        'transaction_id',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(RawMaterialDocument::class);
    }
}
