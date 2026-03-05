<?php

namespace App\Livewire\Inventory\Responsibles;

use App\Models\Inventory\Responsible;
use App\Traits\Livewire\Tables\HasLivewireTableBehavior;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Session;
use Livewire\Component;

class ResponsiblesTable extends Component
{
    use HasLivewireTableBehavior;

    #[Session]
    public string $searchTerm = '';

    #[Session]
    public int $perPage = 12;

    #[Session]
    public int $page = 1;

    #[Session]
    public string $sortColumn = 'name';

    #[Session]
    public string $sortDirection = 'desc';

    protected array $theadConfig = [
        [
            'column' => 'name',
            'label'  => 'Nombre',
            'style'  => 'min-width: 200px;',
        ],
        [
            'column' => 'identifier',
            'label'  => 'Identificador',
        ],
        [
            'column' => 'department',
            'label'  => 'Departamento',
        ],
        [
            'column' => 'position',
            'label'  => 'Posición',
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
        $responsibles = $this->getQuery()->paginate($this->perPage);

        return view('livewire.inventory.responsibles.responsibles-table', [
            'responsibles' => $responsibles,
        ]);
    }

    private function getQuery(): Builder
    {
        $query = Responsible::query();

        if ($term = $this->searchTerm) {
            $query->where(function ($q) use ($term) {
                $q->whereAny(
                    ['name', 'identifier', 'department', 'position'],
                    'like',
                    "%$term%"
                );
            });
        }

        $nullableSortColumns = ['identifier', 'department', 'position'];

        if (in_array($this->sortColumn, $nullableSortColumns)) {
            $query->orderByRaw("{$this->sortColumn} IS NULL")
                ->orderBy($this->sortColumn, $this->sortDirection);

            return $query;
        }

        $query->orderBy($this->sortColumn, $this->sortDirection);

        return $query;
    }
}
