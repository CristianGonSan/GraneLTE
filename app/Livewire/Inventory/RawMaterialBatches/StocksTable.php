<?php

namespace App\Livewire\Inventory\RawMaterialBatches;

use App\Models\Inventory\RawMaterialStock;
use App\Traits\Livewire\Tables\HasLivewireTableBehavior;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Locked;
use Livewire\Component;

class StocksTable extends Component
{
    use HasLivewireTableBehavior;

    #[Locked]
    public int $batchId;

    public string $searchTerm = '';

    public int $perPage = 12;

    public int $page = 1;

    public string $sortColumn = 'material';

    public string $sortDirection = 'desc';

    public array $filters = [
        'quantityMin' => 0.001,
        'quantityMax' => null,
    ];

    protected array $theadConfig = [
        [
            'column' => 'material',
            'label'  => 'Material',
        ],
        [
            'column' => 'warehouse',
            'label'  => 'Almacén',
        ],
        [
            'column' => 'current_quantity',
            'label'  => 'En stock',
        ],
        [
            'label' => 'Ver más',
            'align' => 'center',
        ],
    ];

    public function mount(int $batchId): void
    {
        $this->batchId = $batchId;
        $this->setPage($this->page);
    }

    public function render(): View
    {
        $stocks = $this->getQuery()->paginate($this->perPage);

        return view('livewire.inventory.raw-material-batches.stocks-table', [
            'stocks' => $stocks,
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
        $query = RawMaterialStock::query()
            ->from('raw_material_stocks as stocks')
            ->leftJoin('raw_material_batches as batches', 'stocks.batch_id', '=', 'batches.id')
            ->leftJoin('raw_materials as materials', 'batches.material_id', '=', 'materials.id')
            ->leftJoin('warehouses', 'stocks.warehouse_id', '=', 'warehouses.id')
            ->with([
                'batch:id,material_id',
                'batch.material:id,name,unit_id',
                'batch.material.unit:id,symbol',
                'warehouse:id,name',
            ])
            ->select('stocks.*');

        $query->whereBatchId($this->batchId);

        if ($this->filters['quantityMin'] !== null) {
            $query->where('stocks.current_quantity', '>=', $this->filters['quantityMin']);
        }

        if ($this->filters['quantityMax'] !== null) {
            $query->where('stocks.current_quantity', '<=', $this->filters['quantityMax']);
        }

        if ($term = $this->searchTerm) {
            $query->where(function (Builder $q) use ($term): void {
                $q->where('materials.name', 'like', "%$term%")
                    ->orWhere('warehouses.name', 'like', "%$term%");
            });
        }

        $sortable = [
            'current_quantity' => 'stocks.current_quantity',
            'material'         => 'materials.name',
            'warehouse'        => 'warehouses.name',
        ];

        $column = $sortable[$this->sortColumn] ?? 'materials.name';

        $query->orderBy($column, $this->sortDirection);

        return $query;
    }
}
