<?php

namespace App\Livewire\Inventory\RawMaterialDocuments\Receipts;

use App\DTO\Inventory\RawMaterialDocuments\ReceiptLineData;
use App\Enums\Inventory\RawMaterialDocument\RawMaterialDocumentStatus;
use App\Enums\Inventory\RawMaterialDocument\RawMaterialDocumentType;
use App\Models\Inventory\RawMaterial;
use App\Models\Inventory\RawMaterialDocument;
use App\Models\Inventory\Warehouse;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Toast;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class ReceiptCreate extends Component
{
    use Toast, FlashToast, WithFileUploads;

    public ?string $effective_at     = null;
    public ?string $reference_type   = null;
    public ?string $reference_number = null;
    public string  $total_cost       = '0.00';
    public ?int    $supplier_id      = null;
    public ?int    $responsible_id   = null;
    public ?string $description      = null;
    public bool    $isDraft          = true;

    public ?TemporaryUploadedFile $attachment = null;

    /** @var array<int, array<string, mixed>> */
    public array $lines = [];

    public int $rawMaterialId = 0;
    public int $warehouseId   = 0;

    public function mount(): void
    {
        $this->effective_at = now()->format('Y-m-d\TH:i');
    }

    public function render(): View
    {
        return view('livewire.inventory.raw-material-documents.receipts.receipt-create');
    }

    /**
     * Se ejecuta cuando cualquier clave dentro de $lines cambia.
     * Solo recalcula si el campo modificado afecta al total.
     */
    public function updatedLines(mixed $value, string $key): void
    {
        $parts = explode('.', $key, 2);

        if (\count($parts) !== 2) {
            return;
        }

        [$rawIndex, $field] = $parts;

        if (!\in_array($field, ['received_quantity', 'received_unit_cost'], true)) {
            return;
        }

        $this->recalculateLine((int) $rawIndex);
        $this->recalculateTotal();
    }

    public function addLine(): void
    {
        $this->resetErrorBag(['rawMaterialId', 'warehouseId']);

        if ($this->rawMaterialId === 0) {
            $this->addError('rawMaterialId', 'Seleccione una materia prima.');
            return;
        }

        if ($this->warehouseId === 0) {
            $this->addError('warehouseId', 'Seleccione un almacén.');
            return;
        }

        $material = RawMaterial::active()
            ->with('unit:id,name,symbol')
            ->find($this->rawMaterialId);

        $warehouse = Warehouse::active()->find($this->warehouseId, ['id', 'name']);

        if ($material === null || $warehouse === null) {
            $this->toastError('Materia prima o almacén no disponible.');
            return;
        }

        $this->lines[] = ReceiptLineData::fromModels($material, $warehouse)->toArray();
    }

    public function removeLine(int $index): void
    {
        unset($this->lines[$index]);
        $this->lines = array_values($this->lines);
        $this->recalculateTotal();
    }

    public function save(): void
    {
        if (empty($this->lines)) {
            $this->toastError('El documento debe tener por lo menos un lote.');
            return;
        }

        $this->recalculateTotal();

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
                'type'       => RawMaterialDocumentType::RECEIPT,
                'status'     => $this->isDraft ? RawMaterialDocumentStatus::DRAFT : RawMaterialDocumentStatus::PENDING,
                'created_by' => Auth::id(),
            ]);

            $document->receipt()->create(Arr::only($validated, [
                'supplier_id',
            ]));

            foreach ($validated['lines'] as $line) {
                $document->receiptLines()->create(Arr::only($line, [
                    'material_id',
                    'warehouse_id',
                    'external_batch_code',
                    'received_quantity',
                    'received_unit_cost',
                    'received_total_cost',
                    'expiration_date',
                ]));
            }

            if ($this->attachment instanceof TemporaryUploadedFile) {
                $document->addMedia($this->attachment->getRealPath())
                    ->usingFileName($this->attachment->getClientOriginalName())
                    ->toMediaCollection(RawMaterialDocument::COLLECTION_ATTACHMENTS);
            }

            $this->flashToastSuccess('Documento creado.');
            redirect()->route('raw-material-documents.receipts.show', $document->id);
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
            'supplier_id'      => ['required', Rule::exists('suppliers', 'id')->where('is_active', true)],
            'responsible_id'   => ['nullable', Rule::exists('responsibles', 'id')->where('is_active', true)],
            'description'      => ['nullable', 'string', 'max:255'],

            'attachment' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:10240'],

            'lines'                       => ['required', 'array', 'min:1'],
            'lines.*.material_id'         => ['required', Rule::exists('raw_materials', 'id')->where('is_active', true)],
            'lines.*.warehouse_id'        => ['required', Rule::exists('warehouses', 'id')->where('is_active', true)],
            'lines.*.external_batch_code' => ['nullable', 'string', 'max:128'],
            'lines.*.received_quantity'   => ['required', 'numeric', 'min:0.001', 'max:999999999.999'],
            'lines.*.received_unit_cost'  => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
            'lines.*.received_total_cost' => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
            'lines.*.expiration_date'     => ['nullable', 'date'],
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

    private function recalculateLine(int $index): void
    {
        if (!isset($this->lines[$index])) {
            return;
        }

        $qty  = $this->normalizeDecimal($this->lines[$index]['received_quantity']  ?? '0');
        $cost = $this->normalizeDecimal($this->lines[$index]['received_unit_cost'] ?? '0');

        $this->lines[$index]['received_total_cost'] = bcmul($qty, $cost, 2);
    }

    private function recalculateTotal(): void
    {
        $total = '0';

        foreach ($this->lines as $line) {
            $total = bcadd(
                $total,
                $this->normalizeDecimal($line['received_total_cost'] ?? '0'),
                2
            );
        }

        $this->total_cost = $total;
    }
}
