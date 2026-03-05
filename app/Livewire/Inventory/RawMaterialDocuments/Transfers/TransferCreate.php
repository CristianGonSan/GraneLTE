<?php

namespace App\Livewire\Inventory\RawMaterialDocuments\Transfers;

use App\Enums\Inventory\RawMaterialDocument\RawMaterialDocumentStatus;
use App\Enums\Inventory\RawMaterialDocument\RawMaterialDocumentType;
use App\Models\Inventory\RawMaterialDocument;
use App\Models\Inventory\RawMaterialStock;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Toast;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class TransferCreate extends Component
{
    use Toast, FlashToast;

    // Datos generales del documento
    public ?string $effective_at     = null;
    public ?string $reference_type   = null;
    public ?string $reference_number = null;
    public ?int    $responsible_id   = null;
    public ?string $description      = null;
    public bool    $isDraft          = true;

    // Datos de presentación de la línea (no se persisten)
    public ?string $raw_material_name = null;
    public ?string $unit_name         = null;
    public ?string $unit_symbol       = null;
    public ?string $warehouse_name    = null;
    public ?string $batch_code        = null;
    public ?string $current_quantity  = null;

    // Datos de la línea (se persisten)
    public ?int    $stock_origin_id   = null;
    public ?int    $warehouse_dest_id = null;
    public ?string $quantity          = null;

    // Estado de validación de cantidad
    public bool $invalidQuantity = false;

    public function mount(): void
    {
        $this->effective_at = now()->format('Y-m-d\TH:i');
    }

    public function render(): View
    {
        return view('livewire.inventory.raw-material-documents.transfers.transfer-create');
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

            $validated['type']       = RawMaterialDocumentType::TRANSFER;
            $validated['status']     = $this->isDraft ? RawMaterialDocumentStatus::DRAFT : RawMaterialDocumentStatus::PENDING;
            $validated['created_by'] = Auth::id();

            $document = RawMaterialDocument::create($validated);
            $document->transferLine()->create($validated);

            $this->flashToastSuccess('Documento creado.');
            redirect()->route('raw-material-documents.transfers.show', $document->id);
        });
    }

    #[On('selectedStock')]
    public function setLine(int $id): void
    {
        $stock = RawMaterialStock::find($id);

        if (!$stock) {
            $this->toastError('Stock no disponible.');
            return;
        }

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
        $this->invalidQuantity   = false;

        $this->recalculateTotals();
        $this->toastSuccess('Stock seleccionado.');
    }

    public function recalculateTotals(): void
    {
        if ($this->stock_origin_id) {
            $this->invalidQuantity = bccomp($this->quantity, $this->current_quantity) == 1;
        }
    }

    public function updatedQuantity(): void
    {
        $this->recalculateTotals();
    }

    protected function rules(): array
    {
        return [
            'effective_at'      => ['required', 'date'],
            'reference_type'    => ['nullable', 'string', 'max:32'],
            'reference_number'  => ['nullable', 'string', 'max:128'],
            'responsible_id'    => ['nullable', Rule::exists('responsibles', 'id')->where('is_active', true)],
            'description'       => ['nullable', 'string', 'max:255'],

            'stock_origin_id'   => ['required', Rule::exists('raw_material_stocks', 'id')],
            'warehouse_dest_id' => ['required', Rule::exists('warehouses', 'id')->where('is_active', true)],
            'quantity'          => ['required', 'numeric', 'min:0.001', 'max:999999999.999'],
        ];
    }
}
