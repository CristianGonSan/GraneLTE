<?php

namespace App\Livewire\Inventory\Suppliers;

use App\Models\Inventory\Supplier;

use App\Traits\Livewire\WithTableSorting;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Session;

class SuppliersTable extends Component
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
            'style'  => 'width: 1%;',
        ],
        [
            'column' => 'name',
            'field' => 'name',
            'label' => 'Nombre',
            'style' => 'min-width: 200px;'
        ],
        [
            'column' => 'email',
            'label' => 'Email',
        ],
        [
            'label' => 'Teléfono',
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
        $suppliers = $this->getQuery()->paginate($this->perPage);

        return view('livewire.inventory.suppliers.suppliers-table', [
            'suppliers' => $suppliers
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
        $query = Supplier::query();

        if ($term = $this->searchTerm) {
            $query->whereAny([
                'name',
                'email',
                'phone'
            ], 'like', "%$term%");
        }

        $query->orderBy($this->sortColumn, $this->sortDirection);

        return $query;
    }
}
