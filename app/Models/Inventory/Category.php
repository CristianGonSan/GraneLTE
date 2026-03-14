<?php

namespace App\Models\Inventory;

use App\Traits\Models\HasActiveState;
use App\Traits\Models\TruncateText;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\DB;

/**
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $current_cost
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Inventory\RawMaterial> $rawMaterials
 * @property-read int|null $raw_materials_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category inactive()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Category extends Model
{
    use HasActiveState, TruncateText;

    protected $table = 'categories';

    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'bool',
    ];

    protected $appends = [
        'current_cost'
    ];

    public function isInUse(): bool
    {
        return $this->rawMaterials()->exists();
    }

    public function currentCost(): Attribute
    {
        return Attribute::make(
            fn() => $this->rawMaterials()
                ->join('raw_material_batches as batches', 'batches.material_id', '=', 'raw_materials.id')
                ->where('batches.current_quantity', '>', 0)
                ->sum(DB::raw('batches.current_quantity * batches.received_unit_cost'))
        );
    }

    public function rawMaterials(): HasMany
    {
        return $this->hasMany(RawMaterial::class);
    }
}
