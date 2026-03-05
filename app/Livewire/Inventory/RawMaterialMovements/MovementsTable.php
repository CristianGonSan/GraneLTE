<?php

namespace App\Livewire\Inventory\RawMaterialMovements;

use App\Enums\Inventory\RawMaterialMovement\RawMaterialMovementType;
use App\Models\Inventory\RawMaterialMovement;
use App\Traits\Livewire\Tables\HasLivewireTableBehavior;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Session;
use Livewire\Component;

class MovementsTable extends Component
{
    use HasLivewireTableBehavior;

    #[Session]
    public string $searchTerm = '';

    #[Session]
    public int $perPage = 12;

    #[Session]
    public int $page = 1;

    #[Session]
    public string $sortColumn = 'id';

    #[Session]
    public string $sortDirection = 'desc';

    #[Session]
    public array $filters = [
        'type'        => 'all',
        'quantityMin' => null,
        'quantityMax' => null,
    ];

    protected array $theadConfig = [
        [
            'column' => 'id',
            'label'  => 'ID',
            'align'  => 'center',
            'style'  => 'width: 1%;',
        ],
        [
            'column' => 'type',
            'label'  => 'Tipo',
        ],
        [
            'column' => 'material',
            'label'  => 'Material',
        ],
        [
            'column' => 'batch',
            'label'  => 'Lote',
        ],
        [
            'column' => 'warehouse',
            'label'  => 'Almacén',
        ],
        [
            'column' => 'quantity',
            'label'  => 'Cantidad',
        ],
        [
            'column' => 'effective_at',
            'label'  => 'Fecha efectiva',
        ],
        [
            'label' => 'Ver más',
            'align' => 'center',
        ],
    ];

    public function mount(): void
    {
        $this->setPage($this->page);
    }

    public function render(): View
    {
        $movements = $this->getQuery()->paginate($this->perPage);

        return view('livewire.inventory.raw-material-movements.movements-table', [
            'movements'   => $movements,
            'typeOptions' => RawMaterialMovementType::options(),
        ]);
    }

    public function updatedFilters(mixed $value, string $key): void
    {
        if ($value === '') {
            $this->filters[$key] = null;
        }

        $this->resetPage();
    }

    private function getQuery(): Builder
    {
        $query = RawMaterialMovement::query()
            ->from('raw_material_movements as movements')
            ->leftJoin('raw_material_batches as batches', 'movements.batch_id', '=', 'batches.id')
            ->leftJoin('raw_materials as materials', 'batches.material_id', '=', 'materials.id')
            ->leftJoin('warehouses', 'movements.warehouse_id', '=', 'warehouses.id')
            ->with([
                'batch:id,external_batch_code,batch_code,material_id',
                'batch.material:id,name,unit_id',
                'batch.material.unit:id,symbol',
                'warehouse:id,name',
            ])
            ->select('movements.*');

        if ($this->filters['type'] !== 'all') {
            $query->whereType($this->filters['type']);
        }

        if ($this->filters['quantityMin'] !== null) {
            $query->where('movements.quantity', '>=', $this->filters['quantityMin']);
        }

        if ($this->filters['quantityMax'] !== null) {
            $query->where('movements.quantity', '<=', $this->filters['quantityMax']);
        }

        if ($term = $this->searchTerm) {
            $query->where(function (Builder $q) use ($term): void {
                $q->where('materials.name', 'like', "%$term%")
                    ->orWhere('batches.external_batch_code', 'like', "%$term%")
                    ->orWhere('batches.batch_code', 'like', "%$term%")
                    ->orWhere('warehouses.name', 'like', "%$term%");
            });
        }

        if ($this->sortColumn === 'type') {
            $cases = collect(RawMaterialMovementType::cases())
                ->map(fn(RawMaterialMovementType $case) => "WHEN '{$case->value}' THEN '{$case->label()}'")
                ->implode(' ');

            $query->orderByRaw("CASE movements.type $cases END {$this->sortDirection}");

            return $query;
        }

        if ($this->sortColumn === 'batch') {
            $query->orderByRaw("COALESCE(batches.external_batch_code, batches.batch_code) {$this->sortDirection}");

            return $query;
        }

        $sortable = [
            'id'           => 'movements.id',
            'quantity'     => 'movements.quantity',
            'effective_at' => 'movements.effective_at',
            'material'     => 'materials.name',
            'warehouse'    => 'warehouses.name',
        ];

        $column = $sortable[$this->sortColumn] ?? 'movements.id';

        $query->orderBy($column, $this->sortDirection);

        return $query;
    }
}
