<?php

namespace App\Livewire\Inventory\Warehouses;

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
    public int $warehouseId;

    public string $searchTerm = '';

    public int $perPage = 12;

    public int $page = 1;

    public string $sortColumn = 'material';

    public string $sortDirection = 'desc';

    public array $filters = [
        'quantityMin'      => 0.001,
        'quantityMax'      => null,
        'expirationFilter' => 'all',
        'expirationDays'   => 30,
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
            'column' => 'current_quantity',
            'label'  => 'En stock',
        ],
        [
            'column' => 'expiration_date',
            'label'  => 'Caducidad',
        ],
        [
            'label' => 'Ver más',
            'align' => 'center',
        ],
    ];

    public function mount(int $warehouseId): void
    {
        $this->warehouseId = $warehouseId;
        $this->setPage($this->page);
    }

    public function render(): View
    {
        $stocks = $this->getQuery()->paginate($this->perPage);

        return view('livewire.inventory.warehouses.stocks-table', [
            'stocks' => $stocks,
        ]);
    }

    public function updatedFilters(mixed $value, string $key): void
    {
        if ($value === '') {
            $this->filters[$key] = null;
        }

        if ($key === 'expirationDays') {
            $this->filters['expirationDays'] = max(1, (int) $this->filters['expirationDays']);
        }

        $this->resetPage();
    }

    private function getQuery(): Builder
    {
        $query = RawMaterialStock::query()
            ->from('raw_material_stocks as stocks')
            ->leftJoin('raw_material_batches as batches', 'stocks.batch_id', '=', 'batches.id')
            ->leftJoin('raw_materials as materials', 'batches.material_id', '=', 'materials.id')
            ->with([
                'batch:id,external_batch_code,batch_code,expiration_date,material_id',
                'batch.material:id,name,unit_id',
                'batch.material.unit:id,symbol',
            ])
            ->select('stocks.*');

        $query->whereWarehouseId($this->warehouseId);

        if ($this->filters['quantityMin'] !== null) {
            $query->where('stocks.current_quantity', '>=', $this->filters['quantityMin']);
        }

        if ($this->filters['quantityMax'] !== null) {
            $query->where('stocks.current_quantity', '<=', $this->filters['quantityMax']);
        }

        match ($this->filters['expirationFilter']) {
            'not_expired'    => $query->where(fn(Builder $q) => $q
                ->whereNull('batches.expiration_date')
                ->orWhere('batches.expiration_date', '>', now())),
            'expiring'       => $query->whereNotNull('batches.expiration_date')
                ->where('batches.expiration_date', '>', now())
                ->where('batches.expiration_date', '<=', now()->addDays($this->filters['expirationDays']))
                ->orderBy('batches.expiration_date', 'asc'),
            'expired'        => $query->whereNotNull('batches.expiration_date')
                ->where('batches.expiration_date', '<=', now()),
            'non_perishable' => $query->whereNull('batches.expiration_date'),
            default          => null,
        };

        if ($term = $this->searchTerm) {
            $query->where(function (Builder $q) use ($term): void {
                $q->where('materials.name', 'like', "%$term%")
                    ->orWhere('batches.external_batch_code', 'like', "%$term%")
                    ->orWhere('batches.batch_code', 'like', "%$term%");
            });
        }

        if ($this->sortColumn === 'batch') {
            $query->orderByRaw("COALESCE(batches.external_batch_code, batches.batch_code) {$this->sortDirection}");

            return $query;
        }

        if ($this->sortColumn === 'expiration_date') {
            $query->orderByRaw("batches.expiration_date IS NULL")
                ->orderBy('batches.expiration_date', $this->sortDirection);

            return $query;
        }

        $sortable = [
            'current_quantity' => 'stocks.current_quantity',
            'material'         => 'materials.name',
        ];

        $column = $sortable[$this->sortColumn] ?? 'materials.name';

        $query->orderBy($column, $this->sortDirection);

        return $query;
    }
}
