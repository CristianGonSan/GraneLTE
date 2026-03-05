<?php

namespace App\Livewire\Inventory\RawMaterialBatches;

use App\Exports\Excel\Inventory\RawMaterialBatchesExport;
use App\Models\Inventory\RawMaterialBatch;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportRawMaterialBatches extends Component
{
    // — Filtros de identificación —
    public ?int $materialId = null;
    public ?int $categoryId = null;
    public ?int $supplierId = null;

    // — Filtros de disponibilidad —
    public ?float $quantityMin = null;
    public ?float $quantityMax = null;

    // — Filtros de fechas —
    public ?string $receivedFrom     = null;
    public ?string $receivedTo       = null;
    public string  $expirationFilter = ''; // '' | expired | expiring | no_expiration

    // — Ordenamiento —
    public string $orderBy        = 'material';
    public string $orderDirection = 'asc';

    public function render(): View
    {
        return view('livewire.inventory.raw-material-batches.export-raw-material-batches', [
            'sortableColumns' => $this->sortableColumns(),
        ]);
    }

    public function export(): BinaryFileResponse
    {
        $query = RawMaterialBatch::query()
            ->from('raw_material_batches as batches')
            ->join('raw_materials as materials', 'materials.id', '=', 'batches.material_id')
            ->join('suppliers',                  'suppliers.id', '=', 'batches.supplier_id')
            ->leftJoin('categories',             'categories.id', '=', 'materials.category_id')
            ->select('batches.*')
            ->when($this->quantityMin !== null, fn(Builder $q) => $q->where('batches.current_quantity', '>=', $this->quantityMin))
            ->when($this->quantityMax !== null, fn(Builder $q) => $q->where('batches.current_quantity', '<=', $this->quantityMax))
            ->when($this->materialId, fn(Builder $q) => $q->where('batches.material_id', $this->materialId))
            ->when($this->materialId === null && $this->categoryId, fn(Builder $q) => $q->where('materials.category_id', $this->categoryId))
            ->when($this->supplierId,   fn(Builder $q) => $q->where('batches.supplier_id', $this->supplierId))
            ->when($this->receivedFrom, fn(Builder $q) => $q->where('batches.received_at', '>=', $this->receivedFrom))
            ->when($this->receivedTo,   fn(Builder $q) => $q->where('batches.received_at', '<=', $this->receivedTo))
            ->when($this->expirationFilter !== '', function (Builder $q): void {
                match ($this->expirationFilter) {
                    'expired'       => $q->whereNotNull('batches.expiration_date')
                        ->where('batches.expiration_date', '<=', now()),
                    'expiring'      => $q->whereNotNull('batches.expiration_date')
                        ->whereBetween('batches.expiration_date', [now(), now()->addDays(30)]),
                    'no_expiration' => $q->whereNull('batches.expiration_date'),
                    default         => null,
                };
            });

        $dir = $this->sanitizedDirection();

        match ($this->orderBy) {
            'current_cost' => $query->orderByRaw("(batches.current_quantity * batches.received_unit_cost) $dir"),
            'code'         => $query->orderByRaw("COALESCE(batches.external_batch_code, batches.batch_code) $dir"),
            default        => $query->orderBy($this->resolveOrderColumn($this->orderBy), $dir),
        };

        return Excel::download(
            new RawMaterialBatchesExport($query),
            'lotes_' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    public function sortableColumns(): array
    {
        return [
            'material'              => 'Material',
            'category'              => 'Categoría',
            'supplier'              => 'Proveedor',
            'code'                  => 'Código de lote',
            'current_quantity'      => 'Cantidad actual',
            'current_cost'          => 'Costo actual',
            'received_quantity'     => 'Cantidad recibida',
            'unit_cost'             => 'Costo unitario',
            'received_total_cost'   => 'Costo total recibido',
            'received_at'           => 'Fecha de recepción',
            'expiration_date'       => 'Fecha de vencimiento',
        ];
    }

    private function sanitizedDirection(): string
    {
        return $this->orderDirection === 'desc' ? 'desc' : 'asc';
    }

    private function resolveOrderColumn(string $key): string
    {
        return match ($key) {
            'material'            => 'materials.name',
            'category'            => 'materials.category_id',
            'supplier'            => 'suppliers.name',
            'current_quantity'    => 'batches.current_quantity',
            'received_quantity'   => 'batches.received_quantity',
            'unit_cost'           => 'batches.received_unit_cost',
            'received_total_cost' => 'batches.received_total_cost',
            'received_at'         => 'batches.received_at',
            'expiration_date'     => 'batches.expiration_date',
            default               => 'materials.name',
        };
    }
}
