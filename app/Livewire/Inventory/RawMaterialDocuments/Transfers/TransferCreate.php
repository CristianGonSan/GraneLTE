<?php

namespace App\Livewire\Inventory\RawMaterialDocuments\Transfers;

use App\DTO\Inventory\RawMaterialDocuments\TransferLineData;
use App\Enums\Inventory\RawMaterialDocument\RawMaterialDocumentStatus;
use App\Enums\Inventory\RawMaterialDocument\RawMaterialDocumentType;
use App\Models\Inventory\RawMaterialDocument;
use App\Models\Inventory\RawMaterialStock;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Toast;
use Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class TransferCreate extends Component
{
    use Toast, FlashToast, WithFileUploads;

    public ?string $effective_at     = null;
    public ?string $reference_type   = null;
    public ?string $reference_number = null;
    public string  $total_cost       = '0.00';
    public ?int    $responsible_id   = null;
    public ?string $description      = null;
    public bool    $isDraft          = true;

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
    public ?string $quantity          = null;

    public bool $invalid_quantity = false;

    public function mount(): void
    {
        $this->effective_at = now()->format('Y-m-d\TH:i');
    }

    public function render(): View
    {
        return view('livewire.inventory.raw-material-documents.transfers.transfer-create');
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

            $document = RawMaterialDocument::create([
                ...Arr::only($validated, [
                    'effective_at',
                    'reference_type',
                    'reference_number',
                    'total_cost',
                    'responsible_id',
                    'description',
                ]),
                'type'       => RawMaterialDocumentType::TRANSFER,
                'status'     => $this->isDraft
                    ? RawMaterialDocumentStatus::DRAFT
                    : RawMaterialDocumentStatus::PENDING,
                'created_by' => Auth::id(),
            ]);

            $document->transferLine()->create(Arr::only($validated, [
                'stock_origin_id',
                'warehouse_dest_id',
                'quantity',
            ]));

            if ($this->attachment instanceof TemporaryUploadedFile) {
                $document->addMedia($this->attachment->getRealPath())
                    ->usingFileName($this->attachment->getClientOriginalName())
                    ->toMediaCollection(RawMaterialDocument::COLLECTION_ATTACHMENTS);
            }

            $this->flashToastSuccess('Documento creado.');
            redirect()->route('raw-material-documents.transfers.show', $document->id);
        });
    }

    /**
     * @return array<string, mixed>
     */
    protected function rules(): array
    {
        return [
            'effective_at'     => ['required', 'date'],
            'reference_type'   => ['nullable', 'string', 'max:32'],
            'reference_number' => ['nullable', 'string', 'max:128'],
            'total_cost'       => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
            'responsible_id'   => ['nullable', Rule::exists('responsibles', 'id')->where('is_active', true)],
            'description'      => ['nullable', 'string', 'max:255'],

            'attachment' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:10240'],

            'stock_origin_id'   => ['required', Rule::exists('raw_material_stocks', 'id')],
            'warehouse_dest_id' => ['required', Rule::exists('warehouses', 'id')->where('is_active', true)],
            'quantity'          => ['required', 'numeric', 'min:0.001', 'max:999999999.999'],
        ];
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
