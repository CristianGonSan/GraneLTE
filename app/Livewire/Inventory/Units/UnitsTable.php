<?php

namespace App\Livewire\Inventory\Units;

use App\Models\Inventory\Unit;

use App\Traits\Livewire\WithTableSorting;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Session;

class UnitsTable extends Component
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


    protected array $theadConfig =
    [
        [
            'column' => 'id',
            'label' => 'id',
            'align' => 'center',
            'style' => 'width: 1%;',
        ],
        [
            'column' => 'name',
            'label' => 'Nombre',
        ],
        [
            'column' => 'symbol',
            'label' => 'Simbolo',
            'align' => 'center',
        ],
        [
            'label' => 'Activo',
            'align' => 'center',
            'style' => 'width: 1%;',
        ],
        [
            'label' => 'Ver más',
            'align' => 'center',
        ],
    ];


    public function render(): View
    {
        $units = $this->getQuery()->paginate($this->perPage);

        return view('livewire.inventory.units.units-table', [
            'units' => $units,
        ]);
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
        $query = Unit::query();

        if ($term = $this->searchTerm) {
            $query->where(function ($q) use ($term) {
                $q->where('symbol', 'like', "$term%")
                    ->orWhere('name', 'like', "%$term%");
            });
        }

        $query->orderBy($this->sortColumn, $this->sortDirection);

        return $query;
    }
}
