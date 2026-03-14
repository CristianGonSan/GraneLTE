<?php

namespace App\Livewire\Inventory\RawMaterialDocuments\Transfers;

use App\Models\Inventory\RawMaterialDocument;
use App\Models\Inventory\RawMaterialStock;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Toast;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class TransferEdit extends Component
{
    use Toast, FlashToast;

    // Común
    public int     $documentId;
    public ?string $effective_at     = null;
    public ?string $reference_type   = null;
    public ?string $reference_number = null;
    public ?int    $responsible_id   = null;
    public ?string $responsibleText  = null;
    public ?string $description      = null;

    // Datos de presentación de la línea (no se persisten)
    public ?string $raw_material_name = null;
    public ?string $unit_name         = null;
    public ?string $unit_symbol       = null;
    public ?string $warehouse_name    = null;
    public ?string $batch_code        = null;
    public ?string $current_quantity  = null;

    // Datos de la línea (se persisten)
    public ?int   $stock_origin_id    = null;
    public ?int   $warehouse_dest_id  = null;
    public ?string $warehouseDestText = null;
    public ?string  $quantity         = null;

    // Estado de validación de cantidad
    public bool $invalidQuantity = false;

    public function mount(int $documentId): void
    {
        $this->documentId = $documentId;

        $document = $this->document();

        $this->effective_at     = $document->effective_at->format('Y-m-d\TH:i');
        $this->reference_type   = $document->reference_type;
        $this->reference_number = $document->reference_number;
        $this->description      = $document->description;

        $responsible           = $document->responsible;
        $this->responsible_id  = $responsible?->id;
        $this->responsibleText = $responsible?->name;

        $line = $document->transferLine()->with(['originStock.batch.material.unit', 'originStock.warehouse', 'warehouseDest'])->first();

        if ($line) {
            $stock      = $line->originStock;
            $batch      = $stock->batch;
            $material   = $batch->material;
            $warehouse  = $stock->warehouse;

            $this->stock_origin_id   = $stock->id;
            $this->raw_material_name = $material->name;
            $this->unit_name         = $material->unit->name;
            $this->unit_symbol       = $material->unit->symbol;
            $this->warehouse_name    = $warehouse->name;
            $this->batch_code        = $batch->code;
            $this->current_quantity  = $stock->current_quantity;
            $this->quantity          = $line->quantity;
            $this->warehouse_dest_id = $line->warehouse_dest_id;
            $this->warehouseDestText = $line->warehouseDest->name;
        }

        $this->recalculateTotals();
    }

    public function render(): View
    {
        return view('livewire.inventory.raw-material-documents.transfers.transfer-edit');
    }

    public function save(): void
    {
        if (!$this->stock_origin_id) {
            $this->toastError('Debe seleccionar el stock de origen.');
            return;
        }

        if (!$this->warehouse_dest_id) {
            $this->toastError('Seleccione el almacén de destino.');
            return;
        }

        if ($this->invalidQuantity) {
            $this->toastError('No hay stock suficiente.');
            return;
        }

        DB::transaction(function () {
            $validated = $this->validate();

            $document = $this->document();
            $document->update($validated);
            $document->transferLine()->updateOrCreate(['document_id' => $document->id], $validated);
        });

        $this->toastSuccess('Documento actualizado.');
    }

    #[On('selectedStock')]
    public function setLine(int $id): void
    {
        $stock = RawMaterialStock::find($id);

        if (!$stock) {
            $this->toastError('Stock no disponible.');
            return;
        }

        $batch     = $stock->batch;
        $material  = $batch->material;
        $warehouse = $stock->warehouse;

        $this->stock_origin_id   = $stock->id;
        $this->raw_material_name = $material->name;
        $this->unit_name         = $material->unit->name;
        $this->unit_symbol       = $material->unit->symbol;
        $this->warehouse_name    = $warehouse->name;
        $this->batch_code        = $batch->code;
        $this->current_quantity  = $stock->current_quantity;
        $this->invalidQuantity   = false;

        $this->recalculateTotals();
        $this->toastSuccess('Stock seleccionado.');
    }

    public function recalculateTotals(): void
    {
        if ($this->stock_origin_id) {
            $this->invalidQuantity = $this->quantity > $this->current_quantity;
        }
    }

    public function updatedQuantity(): void
    {
        $this->recalculateTotals();
    }

    protected function rules(): array
    {
        $rules = [
            'effective_at'      => ['required', 'date'],
            'reference_type'    => ['nullable', 'string', 'max:32'],
            'reference_number'  => ['nullable', 'string', 'max:128'],
            'description'       => ['nullable', 'string', 'max:255'],

            'stock_origin_id'   => ['required', Rule::exists('raw_material_stocks', 'id')],
            'warehouse_dest_id' => ['required', Rule::exists('warehouses', 'id')->where('is_active', true)],
            'quantity'          => ['required', 'numeric', 'min:0.001', 'max:999999999.999'],
        ];

        $document = $this->document();

        if ($this->responsible_id != $document->responsible_id) {
            $rules['responsible_id'] = [
                'nullable',
                Rule::exists('responsibles', 'id')->where('is_active', true),
            ];
        }

        return $rules;
    }

    private ?RawMaterialDocument $document = null;

    private function document(): RawMaterialDocument
    {
        return $this->document ??= RawMaterialDocument::findOrFail($this->documentId);
    }
}
