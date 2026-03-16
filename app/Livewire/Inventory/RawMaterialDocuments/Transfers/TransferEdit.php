<?php

namespace App\Livewire\Inventory\RawMaterialDocuments\Transfers;

use App\DTO\Inventory\RawMaterialDocuments\TransferLineData;
use App\Models\Inventory\RawMaterialDocument;
use App\Models\Inventory\RawMaterialStock;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Toast;
use Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class TransferEdit extends Component
{
    use Toast, FlashToast, WithFileUploads;

    public int     $documentId;
    public ?string $effective_at     = null;
    public ?string $reference_type   = null;
    public ?string $reference_number = null;
    public string  $total_cost       = '0.00';
    public ?int    $responsible_id   = null;
    public ?string $responsibleText  = null;
    public ?string $description      = null;

    public ?TemporaryUploadedFile $attachment = null;

    // Campos de presentacion de la linea (no se persisten)
    public ?string $raw_material_name = null;
    public ?string $unit_name         = null;
    public ?string $unit_symbol       = null;
    public ?string $warehouse_name    = null;
    public ?string $batch_code        = null;
    public ?string $current_quantity  = null;
    public ?string $unit_cost         = null;

    // Campos de la linea (se persisten)
    public ?int    $stock_origin_id   = null;
    public ?int    $warehouse_dest_id = null;
    public ?string $warehouseDestText = null;
    public ?string $quantity          = null;

    public bool $invalid_quantity = false;

    public function mount(int $documentId): void
    {
        $this->documentId = $documentId;

        $document = $this->document();

        $this->effective_at     = $document->effective_at->format('Y-m-d\TH:i');
        $this->reference_type   = $document->reference_type;
        $this->reference_number = $document->reference_number;
        $this->total_cost       = $document->total_cost ?? '0.00';
        $this->description      = $document->description;

        $responsible           = $document->responsible;
        $this->responsible_id  = $responsible?->id;
        $this->responsibleText = $responsible?->name;

        $line = $document->transferLine()
            ->with(['originStock.batch.material.unit', 'originStock.warehouse', 'warehouseDest'])
            ->first();

        if ($line !== null) {
            $stock    = $line->originStock;
            $batch    = $stock->batch;
            $material = $batch->material;

            $this->stock_origin_id   = $stock->id;
            $this->raw_material_name = $material->name;
            $this->unit_name         = $material->unit->name;
            $this->unit_symbol       = $material->unit->symbol;
            $this->warehouse_name    = $stock->warehouse->name;
            $this->batch_code        = $batch->code;
            $this->unit_cost         = $batch->received_unit_cost;
            $this->current_quantity  = $stock->current_quantity;
            $this->quantity          = $line->quantity;
            $this->warehouse_dest_id = $line->warehouse_dest_id;
            $this->warehouseDestText = $line->warehouseDest->name;
        }

        $this->recalculate();
    }

    public function render(): View
    {
        return view('livewire.inventory.raw-material-documents.transfers.transfer-edit');
    }

    public function updatedQuantity(): void
    {
        $this->recalculate();
    }

    #[On('selectedStock')]
    public function setLine(int $id): void
    {
        $stock = RawMaterialStock::with([
            'batch.material.unit',
            'warehouse',
        ])->find($id);

        if ($stock === null) {
            $this->toastError('Stock no disponible.');
            return;
        }

        $dto = TransferLineData::fromStock($stock, 32);

        $this->stock_origin_id   = $dto->stock_origin_id;
        $this->raw_material_name = $dto->raw_material_name;
        $this->unit_name         = $dto->unit_name;
        $this->unit_symbol       = $dto->unit_symbol;
        $this->warehouse_name    = $dto->warehouse_name;
        $this->batch_code        = $dto->batch_code;
        $this->unit_cost         = $dto->unit_cost;
        $this->current_quantity  = $dto->current_quantity;
        $this->quantity          = null;
        $this->invalid_quantity  = false;
        $this->total_cost        = '0.00';

        $this->toastSuccess('Stock seleccionado.');
    }

    public function save(): void
    {
        if ($this->stock_origin_id === null) {
            $this->toastError('Debe seleccionar el stock de origen.');
            return;
        }

        if ($this->warehouse_dest_id === null) {
            $this->toastError('Seleccione el almacén de destino.');
            return;
        }

        if ($this->invalid_quantity) {
            $this->toastError('No hay stock suficiente.');
            return;
        }

        $this->recalculate();

        DB::transaction(function (): void {
            $validated = $this->validate();

            $document = $this->document();

            $document->update(Arr::only($validated, [
                'effective_at',
                'reference_type',
                'reference_number',
                'total_cost',
                'responsible_id',
                'description',
            ]));

            $document->transferLine()->updateOrCreate(
                ['document_id' => $document->id],
                Arr::only($validated, [
                    'stock_origin_id',
                    'warehouse_dest_id',
                    'quantity',
                ])
            );

            if ($this->attachment instanceof TemporaryUploadedFile) {
                $document->clearMediaCollection(RawMaterialDocument::COLLECTION_ATTACHMENTS);

                $document->addMedia($this->attachment->getRealPath())
                    ->usingFileName($this->attachment->getClientOriginalName())
                    ->toMediaCollection(RawMaterialDocument::COLLECTION_ATTACHMENTS);

                $this->attachment = null;
            }
        });

        $this->toastSuccess('Documento actualizado.');
    }

    /**
     * @return array<string, mixed>
     */
    protected function rules(): array
    {
        $rules = [
            'effective_at'     => ['required', 'date'],
            'reference_type'   => ['nullable', 'string', 'max:32'],
            'reference_number' => ['nullable', 'string', 'max:128'],
            'total_cost'       => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
            'description'      => ['nullable', 'string', 'max:255'],

            'attachment' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:10240'],

            'stock_origin_id'   => ['required', Rule::exists('raw_material_stocks', 'id')],
            'warehouse_dest_id' => ['required', Rule::exists('warehouses', 'id')->where('is_active', true)],
            'quantity'          => ['required', 'numeric', 'min:0.001', 'max:999999999.999'],
        ];

        if ($this->responsible_id !== $this->document()->responsible_id) {
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

    /**
     * Normaliza un valor de entrada a string decimal compatible con bcmath.
     * Convierte coma a punto, descarta blancos y valores no numericos.
     */
    private function normalizeDecimal(mixed $value): string
    {
        $normalized = str_replace(',', '.', trim((string) $value));

        return is_numeric($normalized) ? $normalized : '0';
    }

    private function recalculate(): void
    {
        if ($this->stock_origin_id === null) {
            return;
        }

        $qty      = $this->normalizeDecimal($this->quantity);
        $current  = $this->normalizeDecimal($this->current_quantity);
        $unitCost = $this->normalizeDecimal($this->unit_cost);

        $this->invalid_quantity = bccomp($qty, $current, 3) > 0;
        $this->total_cost       = bcmul($qty, $unitCost, 2);
    }
}
