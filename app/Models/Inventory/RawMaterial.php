<?php

namespace App\Models\Inventory;

use App\Traits\Models\HasActiveState;
use App\Traits\Models\TruncateText;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Str;

/**
 * @property int $id
 * @property string $name
 * @property string $abbreviation
 * @property string|null $description
 * @property numeric|null $minimum_stock
 * @property int $unit_id
 * @property int|null $category_id
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Inventory\RawMaterialBatch> $batches
 * @property-read int|null $batches_count
 * @property-read \App\Models\Inventory\Category|null $category
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Inventory\RawMaterialStock> $stocks
 * @property-read int|null $stocks_count
 * @property-read \App\Models\Inventory\Unit $unit
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterial active()
 * @method static \Database\Factories\Inventory\RawMaterialFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterial inactive()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterial newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterial newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterial query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterial whereAbbreviation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterial whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterial whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterial whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterial whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterial whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterial whereMinimumStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterial whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterial whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterial whereUpdatedAt($value)
 * @mixin \Eloquent
 * @mixin IdeHelperRawMaterial
 */
class RawMaterial extends Model
{
    use HasFactory, HasActiveState, TruncateText;

    protected $table = 'raw_materials';

    protected $fillable = [
        'name',
        'abbreviation',
        'description',
        'minimum_stock',
        'current_quantity',
        'unit_id',
        'category_id',
        'is_active',
    ];

    protected $casts = [
        'minimum_stock'     => 'decimal:3',
        'current_quantity'  => 'decimal:3',
        'is_active'         => 'boolean',
    ];

    public function generateBatchCode(): string
    {
        $abbreviation   = $this->abbreviation;
        $date           = now()->format('my');
        $random         = Str::upper(Str::random(6));
        return "$abbreviation-$date-$random";
    }

    public function generateBatchCodeUnique(): string
    {
        $code = $this->generateBatchCode();

        while (RawMaterialBatch::where('batch_code', '=', $code)->exists()) {
            $code = $this->generateBatchCode();
        }

        return $code;
    }

    public function isLowStock(): bool
    {
        if ($this->minimum_stock <= 0) {
            return false;
        }

        return $this->current_quantity < $this->minimum_stock;
    }

    public function isInUse(): bool
    {
        return $this->batches()->exists();
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function batches(): HasMany
    {
        return $this->hasMany(RawMaterialBatch::class, 'material_id');
    }

    public function stocks(): HasManyThrough
    {
        return $this->hasManyThrough(
            RawMaterialStock::class,
            RawMaterialBatch::class
        );
    }
}
