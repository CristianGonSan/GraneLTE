<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use App\Traits\Livewire\WithTableSorting;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Session;

class UsersTable extends Component
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
            'column' => 'id',
            'label' => 'ID',
            'align' => 'center',
            'style' => 'width: 1%;',
        ],
        [
            'column' => 'name',
            'label' => 'Nombre',
        ],
        [
            'column' => 'email',
            'label' => 'Email',
        ],
        [
            'label' => 'Activo',
            'align' => 'center',
            'style' => 'width: 1%;',
        ],
        [
            'label' => 'Acciones',
            'align' => 'center',
        ],
    ];

    public function render(): View
    {
        $users = $this->getQuery()->paginate($this->perPage);

        return view('livewire.admin.users.users-table', [
            'users' => $users
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
        $query = User::query();

        if ($term = $this->searchTerm) {
            $query->whereAny([
                'name',
                'email'
            ], 'like', "%$term%");
        }

        $query->orderBy($this->sortColumn, $this->sortDirection);

        return $query;
    }
}
