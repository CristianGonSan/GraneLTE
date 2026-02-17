<?php

namespace App\Livewire\Admin\Roles;

use App\Traits\Livewire\WithTableSorting;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Session;

class RolesTable extends Component
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
            'label' => 'Ver más',
            'align' => 'center',
        ],
    ];


    public function render(): View
    {
        $roles = $this->getQuery()->paginate($this->perPage);

        return view('livewire.admin.roles.roles-table', [
            'roles' => $roles
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
        $query = Role::query();

        if ($term = $this->searchTerm) {
            $query->where('name', 'like', "%$term%");
        }

        $query->orderBy($this->sortColumn, $this->sortDirection);

        return $query;
    }
}
