<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models\Inventory{
/**
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
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
	#[\AllowDynamicProperties]
	class IdeHelperCategory {}
}

namespace App\Models\Inventory{
/**
 * @property int $id
 * @property string $name
 * @property string $abbreviation
 * @property string|null $description
 * @property numeric|null $minimum_stock
 * @property numeric $current_quantity
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterial whereCurrentQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterial whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterial whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterial whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterial whereMinimumStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterial whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterial whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterial whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperRawMaterial {}
}

namespace App\Models\Inventory{
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
 * @property-read \App\Models\Inventory\RawMaterial $material
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Inventory\RawMaterialMovement> $movements
 * @property-read int|null $movements_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Inventory\RawMaterialStock> $stocks
 * @property-read int|null $stocks_count
 * @property-read \App\Models\Inventory\Supplier $supplier
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialBatch active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialBatch fEFO()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialBatch fIFO()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialBatch inactive()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialBatch lIFO()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialBatch newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialBatch newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialBatch query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialBatch whereBatchCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialBatch whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialBatch whereCurrentQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialBatch whereExpirationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialBatch whereExternalBatchCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialBatch whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialBatch whereMaterialId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialBatch whereReceivedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialBatch whereReceivedQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialBatch whereReceivedTotalCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialBatch whereReceivedUnitCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialBatch whereSupplierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialBatch whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperRawMaterialBatch {}
}

namespace App\Models\Inventory{
/**
 * @property int $id
 * @property \App\Enums\Inventory\RawMaterialDocument\RawMaterialDocumentType $type
 * @property \App\Enums\Inventory\RawMaterialDocument\RawMaterialDocumentStatus $status
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
 * @property-read \App\Models\User $creator
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Inventory\RawMaterialReceiptLine> $issueLines
 * @property-read int|null $issue_lines_count
 * @property-read \App\Models\Inventory\RawMaterialReceipt|null $receipt
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Inventory\RawMaterialReceiptLine> $receiptLines
 * @property-read int|null $receipt_lines_count
 * @property-read \App\Models\Inventory\Responsible|null $responsible
 * @property-read \App\Models\User|null $validator
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
 */
	#[\AllowDynamicProperties]
	class IdeHelperRawMaterialDocument {}
}

namespace App\Models\Inventory{
/**
 * @property int $id
 * @property \App\Enums\Inventory\RawMaterialMovement\MovementType $type
 * @property numeric $quantity
 * @property \Illuminate\Support\Carbon $effective_at
 * @property int $batch_id
 * @property int $warehouse_id
 * @property int $document_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Inventory\RawMaterialBatch $batch
 * @property-read \App\Models\Inventory\RawMaterialDocument $document
 * @property-read \App\Models\Inventory\RawMaterialStock|null $stock
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
 */
	#[\AllowDynamicProperties]
	class IdeHelperRawMaterialMovement {}
}

namespace App\Models\Inventory{
/**
 * @property int $id
 * @property int $supplier_id
 * @property int $document_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Inventory\RawMaterialDocument $document
 * @property-read \App\Models\Inventory\Supplier $supplier
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
	#[\AllowDynamicProperties]
	class IdeHelperRawMaterialReceipt {}
}

namespace App\Models\Inventory{
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
 * @property-read \App\Models\Inventory\RawMaterial $material
 * @property-read \App\Models\Inventory\Warehouse $warehouse
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
 */
	#[\AllowDynamicProperties]
	class IdeHelperRawMaterialReceiptLine {}
}

namespace App\Models\Inventory{
/**
 * @property int $id
 * @property string $current_quantity
 * @property int $batch_id
 * @property int $warehouse_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Inventory\RawMaterialBatch $batch
 * @property-read \App\Models\Inventory\Warehouse $warehouse
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialStock newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialStock newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialStock query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialStock whereBatchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialStock whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialStock whereCurrentQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialStock whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialStock whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RawMaterialStock whereWarehouseId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperRawMaterialStock {}
}

namespace App\Models\Inventory{
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
 * @method static \Database\Factories\Inventory\ResponsibleFactory factory($count = null, $state = [])
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
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperResponsible {}
}

namespace App\Models\Inventory{
/**
 * @property int $id
 * @property string $name
 * @property string|null $contact_person
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $address
 * @property string|null $description
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Inventory\RawMaterialBatch> $rawMaterialBatches
 * @property-read int|null $raw_material_batches_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier active()
 * @method static \Database\Factories\Inventory\SupplierFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier inactive()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereContactPerson($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperSupplier {}
}

namespace App\Models\Inventory{
/**
 * @property int $id
 * @property string $name
 * @property string $symbol
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Inventory\RawMaterial> $rawMaterials
 * @property-read int|null $raw_materials_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit inactive()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit whereSymbol($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperUnit {}
}

namespace App\Models\Inventory{
/**
 * @property int $id
 * @property string $name
 * @property string|null $location
 * @property string|null $description
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Inventory\RawMaterialBatch> $rawMaterialBatches
 * @property-read int|null $raw_material_batches_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Inventory\RawMaterialStock> $rawMaterialStocks
 * @property-read int|null $raw_material_stocks_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse active()
 * @method static \Database\Factories\Inventory\WarehouseFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse inactive()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperWarehouse {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property bool $is_active
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User active()
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User inactive()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutRole($roles, $guard = null)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperUser {}
}

