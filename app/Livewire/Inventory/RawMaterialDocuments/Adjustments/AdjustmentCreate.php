<?php

namespace App\Livewire\Inventory\RawMaterialDocuments\Adjustments;

use App\Enums\Inventory\RawMaterialDocument\RawMaterialDocumentStatus;
use App\Enums\Inventory\RawMaterialDocument\RawMaterialDocumentType;
use App\Models\Inventory\RawMaterial;
use App\Models\Inventory\RawMaterialDocument;
use App\Models\Inventory\RawMaterialStock;
use App\Models\Inventory\Warehouse;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Toast;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class AdjustmentCreate extends Component
{
    use Toast, FlashToast;

    //Comun
    public ?string $effective_at = null;
    public ?string $reference_type = null;
    public ?string $reference_number = null;
    public ?int $responsible_id = null;
    public ?string $description = null;

    public bool $isDraft = true;

    //Dominio
    public array $lines = [];


    public function mount(): void
    {
        $this->effective_at = now()->format('Y-m-d\TH:i');
    }

    public function render(): View
    {
        return view('livewire.inventory.raw-material-documents.adjustments.adjustment-create');
    }

    public function save(): void
    {
        if (empty($this->lines)) {
            $this->toastError('El documento debe tener por lo menos una linea.');
            return;
        }

        foreach ($this->lines as $line) {
            if ($line['difference_quantity'] == 0) {
                $this->toastError('La diferencia no puede ser 0');
                return;
            }
        }

        DB::transaction(function () {
            $validated = $this->validate();

            $validated['type']       = RawMaterialDocumentType::ADJUSTMENT;
            $validated['status']     = $this->isDraft ? RawMaterialDocumentStatus::DRAFT : RawMaterialDocumentStatus::PENDING;
            $validated['created_by'] = Auth::id();

            $document = RawMaterialDocument::create($validated);

            foreach ($validated['lines'] as $line) {
                $document->adjustmentLines()->create($line);
            }

            $this->flashToastSuccess('Documento creado.');
            redirect()->route('raw-material-documents.adjustments.show', $document->id);
        });
    }

    #[On('selectedStock')]
    public function addLine(int $id): void
    {
        foreach ($this->lines as $line) {
            if ($line['stock_id'] == $id) {
                $this->toastWarning('Stock ya seleccionado.');
                return;
            }
        }

        $stock = RawMaterialStock::find($id);

        if (!$stock) {
            $this->toastError('Stock no disponible.');
            return;
        }

        $batch      = $stock->batch;
        $material   = $batch->material;
        $warehouse  = $stock->warehouse;

        $this->lines[] = [
            'stock_id'              => $stock->id,
            'raw_material_name'     => $material->name,
            'unit_name'             => $material->unit->name,
            'unit_symbol'           => $material->unit->symbol,
            'warehouse_name'        => $warehouse->name,
            'batch_code'            => $batch->code,
            'theoretical_quantity'  => $stock->current_quantity,
            'counted_quantity'      => null,
            'difference_quantity'   => null
        ];

        $this->recalculateTotals();
        $this->toastSuccess('Stock seleccionado.');
    }

    public function removeLine(string $index): void
    {
        unset($this->lines[$index]);
        $this->recalculateTotals();
    }

    public function recalculateTotals(): void
    {
        foreach ($this->lines as &$line) {
            $differenceQuantity = bcsub(
                $line['counted_quantity'],
                $line['theoretical_quantity'],
                3
            );

            $line['difference_quantity'] = $differenceQuantity;
        }
    }

    public function updatedLines(): void
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

            'lines.*.stock_id'              => ['required', Rule::exists('raw_material_stocks', 'id')],
            'lines.*.theoretical_quantity'  => ['required', 'numeric', 'min:0', 'max:999999999.999'],
            'lines.*.counted_quantity'      => ['required', 'numeric', 'min:0', 'max:999999999.999'],
            'lines.*.difference_quantity'   => ['required', 'numeric', 'min:-999999999.999', 'max:999999999.999'],
        ];
    }
}
