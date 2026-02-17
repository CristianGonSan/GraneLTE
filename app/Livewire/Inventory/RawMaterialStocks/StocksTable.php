<?php

namespace App\Livewire\Inventory\RawMaterialStocks;

use App\Models\Inventory\RawMaterialStock;
use App\Traits\Livewire\WithTableSorting;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Session;

class StocksTable extends Component
{
    use WithPagination, WithTableSorting;

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

    protected array $theadConfig = [
        [
            'label'  => 'Disponible',
            'column' => 'current_quantity',
            'align'  => 'center',
        ],
        [
            'label'  => 'Material',
        ],
        [
            'label'  => 'Lote',
        ],
        [
            'label'  => 'Almacén',
        ],
        [
            'label'  => 'Ver más',
            'align'  => 'center',
        ],
    ];

    public function render(): View
    {
        $stocks = $this->getQuery()->paginate($this->perPage);

        return view(
            'livewire.inventory.raw-material-stocks.stocks-table',
            [
                'stocks' => $stocks,
            ]
        );
    }

    public function search(): void
    {
        $this->resetPage();
    }

    public function afterSortChanged(): void
    {
        $this->resetPage();
    }

    public function updatingPage($page): void
    {
        $this->page = $page;
    }

    private function getQuery(): Builder
    {
        $query = RawMaterialStock::query()
            ->with([
                'batch:id,material_id,batch_code,external_batch_code',
                'warehouse:id,name',
            ]);

        $query->where('current_quantity', '>', 0);

        if ($term = $this->searchTerm) {
            $query->where(function (Builder $q) use ($term) {
                $q->whereHas('batch', function (Builder $batch) use ($term) {
                    $batch->where('external_batch_code', 'like', "%{$term}%")
                        ->orWhere('batch_code', 'like', "%{$term}%");
                });
            });
        }

        $query->orderBy($this->sortColumn, $this->sortDirection);

        return $query;
    }
}
