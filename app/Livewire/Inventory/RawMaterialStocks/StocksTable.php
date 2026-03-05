<?php

namespace App\Livewire\Inventory\RawMaterialStocks;

use App\Models\Inventory\RawMaterialStock;
use App\Traits\Livewire\Tables\HasLivewireTableBehavior;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Session;
use Livewire\Component;

class StocksTable extends Component
{
    use HasLivewireTableBehavior;

    #[Session]
    public string $searchTerm = '';

    #[Session]
    public int $perPage = 12;

    #[Session]
    public int $page = 1;

    #[Session]
    public string $sortColumn = 'material';

    #[Session]
    public string $sortDirection = 'desc';

    #[Session]
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
            'column' => 'batch',
            'label'  => 'Lote',
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

    public function mount(): void
    {
        $this->setPage($this->page);
    }

    public function render(): View
    {
        $stocks = $this->getQuery()->paginate($this->perPage);

        return view('livewire.inventory.raw-material-stocks.stocks-table', [
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
                'batch:id,external_batch_code,batch_code,material_id',
                'batch.material:id,name,unit_id',
                'batch.material.unit:id,symbol',
                'warehouse:id,name',
            ])
            ->select('stocks.*');

        if ($this->filters['quantityMin'] !== null) {
            $query->where('stocks.current_quantity', '>=', $this->filters['quantityMin']);
        }

        if ($this->filters['quantityMax'] !== null) {
            $query->where('stocks.current_quantity', '<=', $this->filters['quantityMax']);
        }

        if ($term = $this->searchTerm) {
            $query->where(function (Builder $q) use ($term): void {
                $q->where('materials.name', 'like', "%$term%")
                    ->orWhere('batches.external_batch_code', 'like', "%$term%")
                    ->orWhere('batches.batch_code', 'like', "%$term%")
                    ->orWhere('warehouses.name', 'like', "%$term%");
            });
        }

        if ($this->sortColumn === 'batch') {
            $query->orderByRaw("COALESCE(batches.external_batch_code, batches.batch_code) {$this->sortDirection}");

            return $query;
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
