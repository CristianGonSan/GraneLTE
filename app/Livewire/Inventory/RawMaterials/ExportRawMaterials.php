<?php

namespace App\Livewire\Inventory\RawMaterials;

use App\Exports\Excel\Inventory\RawMaterialsExport;
use App\Models\Inventory\RawMaterial;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportRawMaterials extends Component
{
    // — Filtros de identificación —
    public ?int $categoryId = null;

    // — Filtros de stock —
    public ?float $quantityMin   = null;
    public ?float $quantityMax   = null;
    public string $lowStockFilter = 'all'; // all | low_stock | ok

    // — Estado —
    public string $activeFilter = 'all'; // active | inactive | all

    // — Ordenamiento —
    public string $orderBy        = 'name';
    public string $orderDirection = 'asc';

    public function render(): View
    {
        return view('livewire.inventory.raw-materials.export-raw-materials', [
            'sortableColumns' => $this->sortableColumns(),
        ]);
    }

    public function export(): BinaryFileResponse
    {
        $query = RawMaterial::query()
            ->leftJoin('categories', 'categories.id', '=', 'raw_materials.category_id')
            ->select('raw_materials.*')
            ->selectRaw(
                '(SELECT COALESCE(SUM(b.current_quantity * b.received_unit_cost), 0)
                    FROM raw_material_batches b
                    WHERE b.material_id = raw_materials.id
                        AND b.current_quantity > 0
                ) as computed_current_cost'
            )
            ->when($this->activeFilter === 'active',   fn(Builder $q) => $q->where('raw_materials.is_active', true))
            ->when($this->activeFilter === 'inactive', fn(Builder $q) => $q->where('raw_materials.is_active', false))
            ->when($this->categoryId, fn(Builder $q) => $q->where('raw_materials.category_id', $this->categoryId))
            ->when(
                $this->lowStockFilter === 'low_stock',
                fn(Builder $q) => $q
                    ->whereColumn('raw_materials.current_quantity', '<', 'raw_materials.minimum_stock')
                    ->where('raw_materials.minimum_stock', '>', 0)
            )
            ->when(
                $this->lowStockFilter === 'ok',
                fn(Builder $q) => $q->where(function (Builder $inner): void {
                    $inner->whereColumn('raw_materials.current_quantity', '>=', 'raw_materials.minimum_stock')
                        ->orWhere('raw_materials.minimum_stock', '<=', 0);
                })
            )
            ->when($this->quantityMin !== null, fn(Builder $q) => $q->where('raw_materials.current_quantity', '>=', $this->quantityMin))
            ->when($this->quantityMax !== null, fn(Builder $q) => $q->where('raw_materials.current_quantity', '<=', $this->quantityMax))
            ->orderBy($this->resolveOrderColumn($this->orderBy), $this->sanitizedDirection());

        return Excel::download(
            new RawMaterialsExport($query),
            'materiales_' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    public function sortableColumns(): array
    {
        return [
            'name'             => 'Material',
            'category'         => 'Categoría',
            'current_quantity' => 'Cantidad actual',
            'current_cost'     => 'Costo actual',
        ];
    }

    private function sanitizedDirection(): string
    {
        return $this->orderDirection === 'desc' ? 'desc' : 'asc';
    }

    private function resolveOrderColumn(string $key): string
    {
        return match ($key) {
            'name'             => 'raw_materials.name',
            'category'         => 'categories.name',
            'current_quantity' => 'raw_materials.current_quantity',
            'current_cost'     => 'computed_current_cost',
            default            => 'raw_materials.name',
        };
    }
}
