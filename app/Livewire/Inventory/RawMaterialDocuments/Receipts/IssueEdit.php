<?php

namespace App\Livewire\Inventory\RawMaterialDocuments\Receipts;

use App\Models\Inventory\RawMaterial;
use App\Models\Inventory\RawMaterialDocument;
use App\Models\Inventory\Warehouse;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Toast;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Component;

class IssueEdit extends Component
{
    use Toast, FlashToast;

    //Comun
    public int $documentId;

    public ?string $effective_at = null;
    public ?string $reference_type = null;
    public ?string $reference_number = null;
    public string $total_cost = '0';
    public ?int $supplier_id = null;
    public ?string $supplierText = null;
    public ?int $responsible_id = null;
    public ?string $responsibleText = null;
    public ?string $description = null;

    //Dominio
    public array $lines = [];

    public int $rawMaterialId = 0;
    public int $warehouseId = 0;

    public function mount(int $documentId): void
    {
        $this->documentId       = $documentId;

        $document               = $this->document();

        $this->effective_at     = $document->effective_at->format('Y-m-d\TH:i');
        $this->reference_type   = $document->reference_type;
        $this->reference_number = $document->reference_number;
        $this->total_cost       = $document->total_cost ?? '0';

        $supplier               = $document->receipt->supplier;
        $this->supplier_id      = $supplier->id;
        $this->supplierText     = $supplier->name;

        $responsible            = $document->responsible;
        $this->responsible_id   = $responsible?->id;
        $this->responsibleText  = $responsible?->name;

        $this->description      = $document->description;

        $this->lines = $document->receiptLines()->with(['material', 'warehouse'])
            ->get()->map(
                fn($line) => [
                    'material_id'           => $line->material->id,
                    'raw_material_name'     => $line->material->name,
                    'unit_name'             => $line->material->unit->name,
                    'unit_symbol'           => $line->material->unit->symbol,
                    'warehouse_id'          => $line->warehouse_id,
                    'warehouse_name'        => $line->warehouse->name,
                    'external_batch_code'   => $line->external_batch_code,
                    'received_quantity'     => $line->received_quantity,
                    'received_unit_cost'    => $line->received_unit_cost,
                    'received_total_cost'   => $line->received_total_cost,
                    'expiration_date'       => $line->expiration_date,
                ]
            )->toArray();

        $this->recalculateTotals();
    }

    public function render(): View
    {
        return view('livewire.inventory.raw-material-documents.receipts.receipt-edit');
    }

    public function save(): void
    {
        if (empty($this->lines)) {
            $this->toastError('El documento debe tener por lo menos un lote.');
            return;
        }

        $validated = $this->validate();

        $document = $this->document();

        $document->update($validated);
        $document->receipt->update($validated);

        $document->receiptLines()->delete();
        foreach ($validated['lines'] as $line) {
            $document->receiptLines()->create($line);
        }

        $this->toastSuccess('Documento actualizado.');
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

        $material = RawMaterial::active()->find($this->rawMaterialId, ['id', 'name', 'unit_id']);
        $warehouse   = Warehouse::active()->find($this->warehouseId, ['id', 'name']);

        if (!$material || !$warehouse) {
            $this->toastError('Materia prima o almacén no disponible.');
            return;
        }

        $this->lines[] = [
            'material_id'           => $material->id,
            'raw_material_name'     => $material->name,
            'unit'                  => $material->unit->only(['name', 'symbol']),
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
        $rules = [
            'effective_at'      => ['required', 'date'],
            'reference_type'    => ['nullable', 'string', 'max:32'],
            'reference_number'  => ['nullable', 'string', 'max:128'],
            'total_cost'        => ['nullable', 'numeric', 'min:0', 'max:9999999999.99'],
            'description'       => ['nullable', 'string', 'max:255'],

            'lines.*.material_id'           => ['required', Rule::exists('raw_materials', 'id')->where('is_active', true)],
            'lines.*.warehouse_id'          => ['required', Rule::exists('warehouses', 'id')->where('is_active', true)],
            'lines.*.external_batch_code'   => ['nullable', 'string', 'max:128'],
            'lines.*.received_quantity'     => ['required', 'numeric', 'min:0.001', 'max:999999999.999'],
            'lines.*.received_total_cost'   => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
            'lines.*.received_unit_cost'    => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
            'lines.*.expiration_date'       => ['nullable', 'date'],
        ];

        $document = $this->document();

        if ($this->supplier_id !== $document->receipt->supplier_id) {
            $rules['supplier_id'] = [
                'required',
                Rule::exists('suppliers', 'id')->where('is_active', true)
            ];
        }

        if ($this->responsible_id !== $document->responsible_id) {
            $rules['responsible_id'] = [
                'nullable',
                Rule::exists('responsibles', 'id')->where('is_active', true)
            ];
        }

        return $rules;
    }

    private function document(): RawMaterialDocument
    {
        if ($this->document == null) {
            $this->document = RawMaterialDocument::findOrFail($this->documentId);
        }

        return $this->document;
    }

    private ?RawMaterialDocument $document = null;
}
