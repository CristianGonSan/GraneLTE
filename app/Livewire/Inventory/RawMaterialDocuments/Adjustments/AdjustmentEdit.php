<?php

namespace App\Livewire\Inventory\RawMaterialDocuments\Adjustments;

use App\DTO\Inventory\RawMaterialDocuments\AdjustmentLineData;
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

class AdjustmentEdit extends Component
{
    use Toast, FlashToast, WithFileUploads;

    public int $documentId;

    public ?string $effective_at     = null;
    public ?string $reference_type   = null;
    public ?string $reference_number = null;
    public string  $total_cost       = '0.00';
    public ?int    $responsible_id   = null;
    public ?string $responsibleText  = null;
    public ?string $description      = null;

    public ?TemporaryUploadedFile $attachment = null;

    /** @var array<int, array<string, mixed>> */
    public array $lines = [];

    public function mount(int $documentId): void
    {
        $this->documentId = $documentId;

        $document = $this->document();

        $this->effective_at     = $document->effective_at->format('Y-m-d\TH:i');
        $this->reference_type   = $document->reference_type;
        $this->reference_number = $document->reference_number;
        $this->total_cost       = $document->total_cost ?? '0.00';

        $responsible           = $document->responsible;
        $this->responsible_id  = $responsible?->id;
        $this->responsibleText = $responsible?->name;

        $this->description = $document->description;

        $this->lines = $document->adjustmentLines()
            ->with(['stock.batch.material.unit', 'stock.warehouse'])
            ->get()
            ->map(function ($line): array {
                $stock    = $line->stock;
                $batch    = $stock->batch;
                $material = $batch->material;

                $theoretical = (string) $line->theoretical_quantity;
                $counted     = (string) $line->counted_quantity;
                $unitCost    = (string) $batch->received_unit_cost;
                $difference  = bcsub($counted, $theoretical, 3);
                $totalCost   = bcmul($difference, $unitCost, 2);

                return [
                    'stock_id'             => $stock->id,
                    'raw_material_name'    => $material->mediumText('name'),
                    'unit_name'            => $material->unit->name,
                    'unit_symbol'          => $material->unit->symbol,
                    'warehouse_name'       => $stock->warehouse->mediumText('name'),
                    'batch_code'           => $batch->code,
                    'unit_cost'            => $unitCost,
                    'theoretical_quantity' => $theoretical,
                    'counted_quantity'     => $counted,
                    'difference_quantity'  => $difference,
                    'total_cost'           => $totalCost,
                    'is_increment'         => bccomp($difference, '0', 3) > 0,
                ];
            })
            ->toArray();

        $this->recalculateTotal();
    }

    public function render(): View
    {
        return view('livewire.inventory.raw-material-documents.adjustments.adjustment-edit');
    }

    /**
     * Se ejecuta cuando cualquier clave dentro de $lines cambia.
     * Solo recalcula si el campo modificado es counted_quantity.
     */
    public function updatedLines(mixed $value, string $key): void
    {
        $parts = explode('.', $key, 2);

        if (\count($parts) !== 2) {
            return;
        }

        [$index, $field] = $parts;

        if ($field !== 'counted_quantity') {
            return;
        }

        $this->recalculateLine((int) $index);
        $this->recalculateTotal();
    }

    #[On('selectedStock')]
    public function addLine(int $id): void
    {
        foreach ($this->lines as $line) {
            if ($line['stock_id'] === $id) {
                $this->toastWarning('Stock ya seleccionado.');
                return;
            }
        }

        $stock = RawMaterialStock::with([
            'batch.material.unit',
            'warehouse',
        ])->find($id);

        if ($stock === null) {
            $this->toastError('Stock no disponible.');
            return;
        }

        $this->lines[] = AdjustmentLineData::fromStock($stock)->toArray();

        $this->toastSuccess('Stock seleccionado.');
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
            $this->toastError('El documento debe tener por lo menos una línea.');
            return;
        }

        foreach ($this->lines as $line) {
            if (bccomp($this->normalizeDecimal($line['difference_quantity'] ?? '0'), '0', 3) === 0) {
                $this->toastError('Hay líneas con diferencia igual a cero.');
                return;
            }
        }

        $this->recalculateTotal();

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

            $document->adjustmentLines()->delete();

            foreach ($validated['lines'] as $line) {
                $document->adjustmentLines()->create(Arr::only($line, [
                    'stock_id',
                    'theoretical_quantity',
                    'counted_quantity',
                    'difference_quantity',
                ]));
            }

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
            'total_cost'       => ['required', 'numeric', 'min:-9999999999.99', 'max:9999999999.99'],
            'description'      => ['nullable', 'string', 'max:255'],

            'attachment' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:10240'],

            'lines'                        => ['required', 'array', 'min:1'],
            'lines.*.stock_id'             => ['required', Rule::exists('raw_material_stocks', 'id')],
            'lines.*.theoretical_quantity' => ['required', 'numeric', 'min:0', 'max:999999999.999'],
            'lines.*.counted_quantity'     => ['required', 'numeric', 'min:0', 'max:999999999.999'],
            'lines.*.difference_quantity'  => ['required', 'numeric', 'min:-999999999.999', 'max:999999999.999'],
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

    private function recalculateLine(int $index): void
    {
        if (!isset($this->lines[$index])) {
            return;
        }

        $rawCounted = $this->lines[$index]['counted_quantity'] ?? null;

        if ($rawCounted === null || trim((string) $rawCounted) === '') {
            $this->lines[$index]['difference_quantity'] = '0.000';
            $this->lines[$index]['total_cost']          = '0.00';
            $this->lines[$index]['is_increment']        = false;
            return;
        }

        $counted     = $this->normalizeDecimal($rawCounted);
        $theoretical = $this->normalizeDecimal($this->lines[$index]['theoretical_quantity'] ?? '0');
        $unitCost    = $this->normalizeDecimal($this->lines[$index]['unit_cost']            ?? '0');

        $difference = bcsub($counted, $theoretical, 3);
        $totalCost  = bcmul($difference, $unitCost, 2);

        $this->lines[$index]['difference_quantity'] = $difference;
        $this->lines[$index]['total_cost']          = $totalCost;
        $this->lines[$index]['is_increment']        = bccomp($difference, '0', 3) > 0;
    }

    private function recalculateTotal(): void
    {
        $total = '0';

        foreach ($this->lines as $line) {
            $total = bcadd(
                $total,
                $this->normalizeDecimal($line['total_cost'] ?? '0'),
                2
            );
        }

        $this->total_cost = $total;
    }
}
