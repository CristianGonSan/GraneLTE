<?php

namespace App\Livewire\Inventory\RawMaterials;

use App\Models\Inventory\RawMaterial;

use App\Traits\Livewire\WithTableSorting;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Session;

class RawMaterialsTable extends Component
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
            'field' => 'name',
            'label' => 'Nombre',
        ],
        [
            'column' => 'abbreviation',
            'label' => 'Abreviatura',
            'align' => 'center',
        ],
        [
            'column' => 'current_quantity',
            'label' => 'Disponible',
            'align' => 'center',
        ],
        [
            'label' => 'Categoría',
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
        $rawMaterials = $this->getQuery()->paginate($this->perPage);

        return view('livewire.inventory.raw-materials.raw-materials-table', [
            'rawMaterials' => $rawMaterials,
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
        $query = RawMaterial::query()
            ->with(['unit:id,symbol', 'category:id,name']);

        if ($term = $this->searchTerm) {
            $query->whereAny([
                'name',
                'abbreviation'
            ], 'like', "%$term%");
        }

        $query->orderBy($this->sortColumn, $this->sortDirection);

        return $query;
    }
}
