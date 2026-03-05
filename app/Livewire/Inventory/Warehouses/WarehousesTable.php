<?php

namespace App\Livewire\Inventory\Warehouses;

use App\Models\Inventory\Warehouse;
use App\Traits\Livewire\Tables\HasLivewireTableBehavior;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Session;
use Livewire\Component;

class WarehousesTable extends Component
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

    protected array $theadConfig = [
        [
            'column' => 'name',
            'label'  => 'Nombre',
            'style'  => 'min-width: 200px;',
        ],
        [
            'label' => 'Descripción',
            'style' => 'min-width: 300px;',
        ],
        [
            'column' => 'is_active',
            'label'  => 'Activo',
            'align'  => 'center',
            'style'  => 'width: 1%;',
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
        $warehouses = $this->getQuery()->paginate($this->perPage);

        return view('livewire.inventory.warehouses.warehouses-table', [
            'warehouses' => $warehouses,
        ]);
    }

    private function getQuery(): Builder
    {
        $query = Warehouse::query();

        if ($term = $this->searchTerm) {
            $query->where(function ($q) use ($term) {
                $q->whereAny(
                    ['name', 'description'],
                    'like',
                    "%$term%"
                );
            });
        }

        $query->orderBy($this->sortColumn, $this->sortDirection);

        return $query;
    }
}
