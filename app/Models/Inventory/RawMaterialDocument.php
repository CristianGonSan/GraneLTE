<?php

namespace App\Models\Inventory;

use App\Actions\Inventory\ExecuteRawMaterialDocument;
use App\Enums\Inventory\RawMaterialDocument\RawMaterialDocumentStatus;
use App\Enums\Inventory\RawMaterialDocument\RawMaterialDocumentType;
use App\Models\User;
use App\Traits\Models\TruncateText;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;


/**
 * @property int $id
 * @property RawMaterialDocumentType $type
 * @property RawMaterialDocumentStatus $status
 * @property \Illuminate\Support\Carbon $effective_at
 * @property string|null $description
 * @property string|null $reference_type
 * @property string|null $reference_number
 * @property numeric|null $total_cost
 * @property int|null $responsible_id
 * @property int $created_by
 * @property int|null $validated_by
 * @property \Illuminate\Support\Carbon|null $validated_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read User $creator
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Inventory\RawMaterialReceiptLine> $issueLines
 * @property-read int|null $issue_lines_count
 * @property-read \App\Models\Inventory\RawMaterialReceipt|null $receipt
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Inventory\RawMaterialReceiptLine> $receiptLines
 * @property-read int|null $receipt_lines_count
 * @property-read \App\Models\Inventory\Responsible|null $responsible
 * @property-read User|null $validator
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialDocument newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialDocument newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialDocument query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialDocument whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialDocument whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialDocument whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialDocument whereEffectiveAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialDocument whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialDocument whereReferenceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialDocument whereReferenceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialDocument whereResponsibleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialDocument whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialDocument whereTotalCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialDocument whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialDocument whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialDocument whereValidatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialDocument whereValidatedBy($value)
 * @mixin \Eloquent
 * @mixin IdeHelperRawMaterialDocument
 */
class RawMaterialDocument extends Model
{
    use HasFactory, TruncateText;

    protected $table = 'raw_material_documents';

    protected $fillable = [
        'type',
        'status',
        'effective_at',
        'description',
        'reference_type',
        'reference_number',
        'total_cost',
        'responsible_id',
        'created_by',
        'validated_by',
        'validated_at',
    ];

    protected $casts = [
        'type'          => RawMaterialDocumentType::class,
        'status'        => RawMaterialDocumentStatus::class,
        'effective_at'  => 'datetime',
        'validated_at'  => 'datetime',
        'total_cost'    => 'decimal:2',
    ];

    public function validateStatusChange(RawMaterialDocumentStatus $newStatus, User $user): bool
    {
        if (!$this->status->canChangeTo($newStatus)) {
            return false;
        }

        if (!RawMaterialDocumentStatus::canChangeBy($newStatus, $user)) {
            return false;
        }

        if ($newStatus === RawMaterialDocumentStatus::PENDING && $this->created_by !== $user->id) {
            return false;
        }

        return true;
    }

    public function execute(): void
    {
        ExecuteRawMaterialDocument::execute($this);
    }

    public function receipt(): HasOne
    {
        return $this->hasOne(
            RawMaterialReceipt::class,
            'document_id'
        );
    }

    public function receiptLines(): HasMany
    {
        return $this->hasMany(
            RawMaterialReceiptLine::class,
            'document_id'
        );
    }

    //Por implementar
    public function issueLines(): HasMany
    {
        return $this->hasMany(
            RawMaterialReceiptLine::class,
            'document_id'
        );
    }

    public function responsible(): BelongsTo
    {
        return $this->belongsTo(Responsible::class, 'responsible_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function validator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    public function getRoute(string $action = 'show'): string
    {
        $prefixName = 'raw-material-documents';

        $routeName = match ($this->type) {
            RawMaterialDocumentType::RECEIPT    => "$prefixName.receipts.$action",
            RawMaterialDocumentType::ISSUE      => "$prefixName.issues.$action",
            RawMaterialDocumentType::TRANSFER   => "$prefixName.transfers.$action",
            RawMaterialDocumentType::ADJUSTMENT => "$prefixName.adjustments.$action",
        };

        return route($routeName, ['document' => $this->id]);
    }

    public function getUrl(string $action = 'show'): string
    {
        $prefixName = 'raw-material-documents';

        $routeName = match ($this->type) {
            RawMaterialDocumentType::RECEIPT    => "$prefixName.receipts.$action",
            RawMaterialDocumentType::ISSUE      => "$prefixName.issues.$action",
            RawMaterialDocumentType::TRANSFER   => "$prefixName.transfers.$action",
            RawMaterialDocumentType::ADJUSTMENT => "$prefixName.adjustments.$action",
        };

        return route($routeName, ['document' => $this->id]);
    }
}
