<?php

namespace App\Livewire\Inventory\RawMaterialMovements;

use App\Enums\Inventory\RawMaterialMovement\RawMaterialMovementType;
use App\Exports\Excel\Inventory\RawMaterialMovementsExport;
use App\Models\Inventory\RawMaterialMovement;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportRawMaterialMovements extends Component
{
    // — Filtros de identificación —
    public ?int $materialId  = null;
    public ?int $categoryId  = null;
    public ?int $warehouseId = null;

    // — Filtros de cantidad —
    public ?float $quantityMin = null;
    public ?float $quantityMax = null;

    // — Filtros de tipo —
    public string $movementType = ''; // '' | receipt | issue | transfer_in | transfer_out | adjustment_in | adjustment_out

    // — Filtros de fechas —
    public ?string $effectiveFrom = null;
    public ?string $effectiveTo   = null;

    // — Ordenamiento —
    public string $orderBy        = 'effective_at';
    public string $orderDirection = 'desc';

    public function render(): View
    {
        return view('livewire.inventory.raw-material-movements.export-raw-material-movements', [
            'sortableColumns' => $this->sortableColumns(),
            'movementTypes'   => RawMaterialMovementType::options(),
        ]);
    }

    public function export(): BinaryFileResponse
    {
        $query = $this->buildQuery();

        $this->applyOrder($query);

        return Excel::download(
            new RawMaterialMovementsExport($query),
            'movimientos_' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    /**
     * @return Builder<RawMaterialMovement>
     */
    private function buildQuery(): Builder
    {
        return RawMaterialMovement::query()
            ->from('raw_material_movements as movements')
            ->join('raw_material_batches as batches', 'batches.id', '=', 'movements.batch_id')
            ->join('raw_materials as materials',      'materials.id', '=', 'batches.material_id')
            ->join('categories',                      'categories.id', '=', 'materials.category_id')
            ->select('movements.*')
            ->when($this->quantityMin !== null, fn(Builder $q) => $q->where('movements.quantity', '>=', $this->quantityMin))
            ->when($this->quantityMax !== null, fn(Builder $q) => $q->where('movements.quantity', '<=', $this->quantityMax))
            ->when($this->materialId,  fn(Builder $q) => $q->where('batches.material_id', $this->materialId))
            ->when($this->materialId === null && $this->categoryId, fn(Builder $q) => $q->where('materials.category_id', $this->categoryId))
            ->when($this->warehouseId,         fn(Builder $q) => $q->where('movements.warehouse_id', $this->warehouseId))
            ->when($this->movementType !== '', fn(Builder $q) => $q->where('movements.type', $this->movementType))
            ->when($this->effectiveFrom,       fn(Builder $q) => $q->where('movements.effective_at', '>=', $this->effectiveFrom))
            ->when($this->effectiveTo,         fn(Builder $q) => $q->where('movements.effective_at', '<=', $this->effectiveTo));
    }

    /**
     * @param Builder<RawMaterialMovement> $query
     */
    private function applyOrder(Builder $query): void
    {
        $dir = $this->sanitizedDirection();

        if ($this->orderBy === 'type') {
            $cases = collect(RawMaterialMovementType::cases())
                ->map(fn(RawMaterialMovementType $case): string => "WHEN '{$case->value}' THEN '{$case->label()}'")
                ->implode(' ');
            $query->orderByRaw("CASE movements.type $cases END $dir");
        } else if ($this->orderBy === 'cost') {
            $query->orderByRaw("(movements.quantity * batches.received_unit_cost) $dir");
        } else {
            $query->orderBy($this->resolveOrderColumn($this->orderBy), $dir);
        }
    }

    public function sortableColumns(): array
    {
        return [
            'effective_at' => 'Fecha efectiva',
            'material'     => 'Material',
            'category'     => 'Categoría',
            'warehouse'    => 'Almacén',
            'type'         => 'Tipo',
            'quantity'     => 'Cantidad',
            'cost'         => 'Costo movido',
        ];
    }

    private function sanitizedDirection(): string
    {
        return $this->orderDirection === 'desc' ? 'desc' : 'asc';
    }

    private function resolveOrderColumn(string $key): string
    {
        return match ($key) {
            'effective_at' => 'movements.effective_at',
            'material'     => 'materials.name',
            'category'     => 'categories.name',
            'warehouse'    => 'movements.warehouse_id',
            'quantity'     => 'movements.quantity',
            default        => 'movements.effective_at',
        };
    }
}
