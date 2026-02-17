<?php

namespace App\Livewire\Inventory\RawMaterialDocuments\Receipts;

use App\Enums\Inventory\RawMaterialDocument\RawMaterialDocumentStatus;
use App\Enums\Inventory\RawMaterialDocument\RawMaterialDocumentType;
use App\Models\Inventory\RawMaterial;
use App\Models\Inventory\RawMaterialDocument;
use App\Models\Inventory\Warehouse;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Toast;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Component;

class ReceiptCreate extends Component
{
    use Toast, FlashToast;

    //Comun
    public ?string $effective_at = null;
    public ?string $reference_type = null;
    public ?string $reference_number = null;
    public string $total_cost = "0";
    public ?int $supplier_id = null;
    public ?int $responsible_id = null;
    public ?string $description = null;

    public bool $isDraft = false;


    //Dominio
    public array $lines = [];

    public int $rawMaterialId = 0;
    public int $warehouseId = 0;


    public function mount(): void
    {
        $this->effective_at = now()->format('Y-m-d\TH:i');
    }

    public function render(): View
    {
        return view('livewire.inventory.raw-material-documents.receipts.receipt-create');
    }

    public function save(): void
    {
        if (empty($this->lines)) {
            $this->toastError('El documento debe tener por lo menos un lote.');
            return;
        }

        $validated = $this->validate();

        $validated['type']       = RawMaterialDocumentType::RECEIPT;
        $validated['status']     = $this->isDraft ? RawMaterialDocumentStatus::DRAFT : RawMaterialDocumentStatus::PENDING;
        $validated['created_by'] = Auth::id();

        $document = RawMaterialDocument::create($validated);
        $document->receipt()->create($validated);

        foreach ($validated['lines'] as $line) {
            $document->receiptLines()->create($line);
        }

        $this->flashToastSuccess('Documento creado.');
        redirect()->route('raw-material-documents.index');
    }

    public function addLine(): void
    {
        $this->resetErrorBag(['rawMaterialId', 'warehouseId']);

        if ($this->rawMaterialId === 0) {
            $this->addError('rawMaterialId', 'Seleccione una materia prima');
            return;
        }

        if ($this->warehouseId === 0) {
            $this->addError('warehouseId', 'Seleccione un almacén');
            return;
        }

        $material    = RawMaterial::active()->find($this->rawMaterialId, ['id', 'name', 'unit_id']);
        $warehouse   = Warehouse::active()->find($this->warehouseId, ['id', 'name']);

        if (!$material || !$warehouse) {
            $this->toastError('Materia prima o almacén no disponible.');
            return;
        }

        $this->lines[] = [
            'material_id'           => $material->id,
            'raw_material_name'     => $material->name,
            'unit_name'             => $material->unit->name,
            'unit_symbol'           => $material->unit->symbol,
            'warehouse_id'          => $warehouse->id,
            'warehouse_name'        => $warehouse->name,
            'external_batch_code'   => null,
            'received_quantity'     => 1,
            'received_unit_cost'    => 1,
            'received_total_cost'   => 1,
            'expiration_date'       => null,
        ];

        $this->recalculateTotals();
    }

    public function removeLine(string $index): void
    {
        unset($this->lines[$index]);
        $this->recalculateTotals();
    }

    public function recalculateTotals(): void
    {
        $totalCost = '0';

        foreach ($this->lines as &$line) {
            $subTotal = bcmul($line['received_quantity'], $line['received_unit_cost'], 2);
            $line['received_total_cost'] = $subTotal;
            $totalCost = bcadd($totalCost, $subTotal, 2);
        }

        $this->total_cost = $totalCost;
    }

    protected function rules(): array
    {
        return [
            'effective_at'      => ['required', 'date'],
            'reference_type'    => ['nullable', 'string', 'max:32'],
            'reference_number'  => ['nullable', 'string', 'max:128'],
            'total_cost'        => ['nullable', 'numeric', 'min:0', 'max:9999999999.99'],
            'supplier_id'       => ['required', Rule::exists('suppliers', 'id')->where('is_active', true)],
            'responsible_id'    => ['nullable', Rule::exists('responsibles', 'id')->where('is_active', true)],
            'description'       => ['nullable', 'string', 'max:255'],

            'lines.*.material_id'           => ['required', Rule::exists('raw_materials', 'id')->where('is_active', true)],
            'lines.*.warehouse_id'          => ['required', Rule::exists('warehouses', 'id')->where('is_active', true)],
            'lines.*.external_batch_code'   => ['nullable', 'string', 'max:128'],
            'lines.*.received_quantity'     => ['required', 'numeric', 'min:0.001', 'max:999999999.999'],
            'lines.*.received_total_cost'   => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
            'lines.*.received_unit_cost'    => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
            'lines.*.expiration_date'       => ['nullable', 'date'],
        ];
    }
}
