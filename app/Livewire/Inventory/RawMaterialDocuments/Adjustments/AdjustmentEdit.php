<?php

namespace App\Livewire\Inventory\RawMaterialDocuments\Adjustments;

use App\Models\Inventory\RawMaterialDocument;
use App\Models\Inventory\RawMaterialStock;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Toast;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class AdjustmentEdit extends Component
{
    use Toast, FlashToast;

    // Comun
    public int $documentId;

    public ?string $effective_at = null;
    public ?string $reference_type = null;
    public ?string $reference_number = null;
    public ?int $responsible_id = null;
    public ?string $responsibleText = null;
    public ?string $description = null;

    // Dominio
    public array $lines = [];

    public function mount(int $documentId): void
    {
        $this->documentId = $documentId;

        $document = $this->document();

        $this->effective_at     = $document->effective_at->format('Y-m-d\TH:i');
        $this->reference_type   = $document->reference_type;
        $this->reference_number = $document->reference_number;

        $responsible            = $document->responsible;
        $this->responsible_id   = $responsible?->id;
        $this->responsibleText  = $responsible?->name;

        $this->description      = $document->description;

        $this->lines = $document->adjustmentLines()
            ->with(['stock.batch.material.unit', 'stock.warehouse'])
            ->get()
            ->map(function ($line) {
                $stock      = $line->stock;
                $batch      = $stock->batch;
                $warehouse  = $stock->warehouse;
                $material   = $batch->material;

                return [
                    'stock_id'             => $stock->id,
                    'raw_material_name'    => $material->name,
                    'unit_name'            => $material->unit->name,
                    'unit_symbol'          => $material->unit->symbol,
                    'warehouse_name'       => $warehouse->name,
                    'batch_code'           => $batch->code(),
                    'theoretical_quantity' => $line->theoretical_quantity,
                    'counted_quantity'     => $line->counted_quantity,
                    'difference_quantity'  => $line->difference_quantity,
                ];
            })
            ->toArray();

        $this->recalculateTotals();
    }

    public function render(): View
    {
        return view('livewire.inventory.raw-material-documents.adjustments.adjustment-edit');
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

            $document = $this->document();
            $document->update($validated);

            $document->adjustmentLines()->delete();

            foreach ($validated['lines'] as $line) {
                $document->adjustmentLines()->create($line);
            }
        });

        $this->toastSuccess('Documento actualizado.');
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
        $rules = [
            'effective_at'      => ['required', 'date'],
            'reference_type'    => ['nullable', 'string', 'max:32'],
            'reference_number'  => ['nullable', 'string', 'max:128'],
            'description'       => ['nullable', 'string', 'max:255'],

            'lines.*.stock_id'             => ['required', Rule::exists('raw_material_stocks', 'id')],
            'lines.*.theoretical_quantity' => ['required', 'numeric', 'min:0.000', 'max:999999999.999'],
            'lines.*.counted_quantity'     => ['required', 'numeric', 'min:0.000', 'max:999999999.999'],
            'lines.*.difference_quantity'  => ['required', 'numeric', 'min:-999999999.999', 'max:999999999.999'],
        ];

        $document = $this->document();

        if ($this->responsible_id != $document->responsible_id) {
            $rules['responsible_id'] = [
                'nullable',
                Rule::exists('responsibles', 'id')->where('is_active', true)
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
