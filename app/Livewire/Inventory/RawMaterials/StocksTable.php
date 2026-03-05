<?php

namespace App\Livewire\Inventory\RawMaterials;

use App\Models\Inventory\RawMaterial;
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
    public int $rawMaterialId;

    public string $unitSymbol;

    public string $searchTerm = '';

    public int $perPage = 12;

    public int $page = 1;

    public string $sortColumn = 'current_quantity';

    public string $sortDirection = 'desc';

    public array $filters = [
        'quantityMin' => 0.001,
        'quantityMax' => null,
    ];

    protected array $theadConfig = [
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

    public function mount(int $rawMaterialId): void
    {
        $material            = RawMaterial::findOrFail($rawMaterialId, ['id', 'unit_id']);
        $this->rawMaterialId = $rawMaterialId;
        $this->unitSymbol    = $material->unit->symbol;

        $this->setPage($this->page);
    }

    public function render(): View
    {
        $stocks = $this->getQuery()->paginate($this->perPage);

        return view('livewire.inventory.raw-materials.stocks-table', [
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
            ->leftJoin('warehouses', 'stocks.warehouse_id', '=', 'warehouses.id')
            ->with([
                'batch:id,external_batch_code,batch_code,material_id',
                'warehouse:id,name',
            ])
            ->select('stocks.*');

        $query->where('batches.material_id', '=', $this->rawMaterialId);

        if ($this->filters['quantityMin'] !== null) {
            $query->where('stocks.current_quantity', '>=', $this->filters['quantityMin']);
        }

        if ($this->filters['quantityMax'] !== null) {
            $query->where('stocks.current_quantity', '<=', $this->filters['quantityMax']);
        }

        if ($term = $this->searchTerm) {
            $query->where(function (Builder $q) use ($term): void {
                $q->where('batches.external_batch_code', 'like', "%$term%")
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
            'warehouse'        => 'warehouses.name',
        ];

        $column = $sortable[$this->sortColumn] ?? 'stocks.current_quantity';

        $query->orderBy($column, $this->sortDirection);

        return $query;
    }
}
