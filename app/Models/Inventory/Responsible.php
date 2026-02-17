<?php

namespace App\Models\Inventory;

use App\Traits\Models\HasActiveState;
use App\Traits\Models\TruncateText;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string|null $identifier
 * @property string|null $position
 * @property string|null $department
 * @property string|null $phone
 * @property string|null $email
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Inventory\RawMaterialDocument> $rawMaterialTransactions
 * @property-read int|null $raw_material_transactions_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Responsible active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Responsible inactive()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Responsible newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Responsible newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Responsible query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Responsible whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Responsible whereDepartment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Responsible whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Responsible whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Responsible whereIdentifier($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Responsible whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Responsible whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Responsible wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Responsible wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Responsible whereUpdatedAt($value)
 * @method static \Database\Factories\Inventory\ResponsibleFactory factory($count = null, $state = [])
 * @mixin \Eloquent
 * @mixin IdeHelperResponsible
 */
class Responsible extends Model
{
    use HasFactory, HasActiveState, TruncateText;

    protected $table = 'responsibles';

    protected $fillable = [
        'name',
        'identifier',
        'position',
        'department',
        'phone',
        'email',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function isInUse(): bool
    {
        return $this->rawMaterialTransactions()->exists();
    }

    public function rawMaterialTransactions(): HasMany
    {
        return $this->hasMany(RawMaterialDocument::class);
    }
}
