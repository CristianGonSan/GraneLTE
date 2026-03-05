<?php

namespace App\Livewire\Inventory\RawMaterialStocks;

use App\Exports\Excel\Inventory\RawMaterialStocksExport;
use App\Models\Inventory\RawMaterialStock;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportRawMaterialStocks extends Component
{
    // — Filtros de identificación —
    public ?int $materialId  = null;
    public ?int $categoryId  = null;
    public ?int $warehouseId = null;
    public ?int $supplierId  = null;

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
        return view('livewire.inventory.raw-material-stocks.export-raw-material-stocks', [
            'sortableColumns' => $this->sortableColumns(),
        ]);
    }

    public function export(): BinaryFileResponse
    {
        $query = RawMaterialStock::query()
            ->join('raw_material_batches as batches', 'batches.id', '=', 'raw_material_stocks.batch_id')
            ->join('raw_materials as materials',      'materials.id', '=', 'batches.material_id')
            ->select('raw_material_stocks.*')
            ->when($this->quantityMin !== null, fn(Builder $q) => $q->where('raw_material_stocks.current_quantity', '>=', $this->quantityMin))
            ->when($this->quantityMax !== null, fn(Builder $q) => $q->where('raw_material_stocks.current_quantity', '<=', $this->quantityMax))
            ->when($this->materialId, fn(Builder $q) => $q->where('batches.material_id', $this->materialId))
            ->when($this->materialId === null && $this->categoryId, fn(Builder $q) => $q->where('materials.category_id', $this->categoryId))
            ->when($this->warehouseId,  fn(Builder $q) => $q->where('raw_material_stocks.warehouse_id', $this->warehouseId))
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

        if ($this->orderBy === 'current_cost') {
            $query->orderByRaw("(raw_material_stocks.current_quantity * batches.received_unit_cost) $dir");
        } else {
            $query->orderBy($this->resolveOrderColumn($this->orderBy), $dir);
        }

        return Excel::download(
            new RawMaterialStocksExport($query),
            'existencias_' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    public function sortableColumns(): array
    {
        return [
            'material'         => 'Material',
            'category'         => 'Categoría',
            'warehouse'        => 'Almacén',
            'supplier'         => 'Proveedor',
            'current_quantity' => 'Cantidad actual',
            'current_cost'     => 'Costo actual',
            'unit_cost'        => 'Costo unitario',
            'received_at'      => 'Fecha de recepción',
            'expiration_date'  => 'Fecha de vencimiento',
        ];
    }

    private function sanitizedDirection(): string
    {
        return $this->orderDirection === 'desc' ? 'desc' : 'asc';
    }

    private function resolveOrderColumn(string $key): string
    {
        return match ($key) {
            'material'         => 'materials.name',
            'category'         => 'materials.category_id',
            'warehouse'        => 'raw_material_stocks.warehouse_id',
            'supplier'         => 'batches.supplier_id',
            'current_quantity' => 'raw_material_stocks.current_quantity',
            'unit_cost'        => 'batches.received_unit_cost',
            'received_at'      => 'batches.received_at',
            'expiration_date'  => 'batches.expiration_date',
            default            => 'materials.name',
        };
    }
}
