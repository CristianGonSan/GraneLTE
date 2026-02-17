<?php

namespace App\Models\Inventory;

use App\Models\Inventory\RawMaterial;
use App\Models\Inventory\Warehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


/**
 * @property int $id
 * @property string|null $external_batch_code
 * @property numeric $received_quantity
 * @property numeric $received_total_cost
 * @property numeric $received_unit_cost
 * @property \Illuminate\Support\Carbon|null $expiration_date
 * @property int $material_id
 * @property int $warehouse_id
 * @property int $document_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Inventory\RawMaterialDocument $document
 * @property-read RawMaterial $material
 * @property-read Warehouse $warehouse
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialReceiptLine newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialReceiptLine newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialReceiptLine query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialReceiptLine whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialReceiptLine whereDocumentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialReceiptLine whereExpirationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialReceiptLine whereExternalBatchCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialReceiptLine whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialReceiptLine whereMaterialId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialReceiptLine whereReceivedQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialReceiptLine whereReceivedTotalCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialReceiptLine whereReceivedUnitCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialReceiptLine whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialReceiptLine whereWarehouseId($value)
 * @mixin \Eloquent
 * @mixin IdeHelperRawMaterialReceiptLine
 */
class RawMaterialReceiptLine extends Model
{
    use HasFactory;

    protected $table = 'raw_material_receipt_lines';

    protected $fillable = [
        'external_batch_code',
        'received_quantity',
        'received_total_cost',
        'received_unit_cost',
        'expiration_date',
        'material_id',
        'warehouse_id',
        'document_id',
    ];

    protected $casts = [
        'received_quantity'    => 'decimal:3',
        'received_total_cost'  => 'decimal:2',
        'received_unit_cost'   => 'decimal:2',
        'expiration_date'      => 'date',
    ];

    public function material(): BelongsTo
    {
        return $this->belongsTo(RawMaterial::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(RawMaterialDocument::class);
    }
}
