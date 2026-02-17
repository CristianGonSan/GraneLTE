<?php

namespace App\Livewire\Inventory\RawMaterialDocuments;

use App\Models\Inventory\RawMaterialDocument;
use App\Traits\Livewire\WithTableSorting;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Session;

class DocumentsTable extends Component
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
            'label'  => 'id',
            'align'  => 'center',
            'style'  => 'width: 1%;',
        ],
        [
            'label'  => 'Tipo',
            'align'  => 'center',
        ],
        [
            'label'  => 'Estado',
            'align'  => 'center',
        ],
        [
            'column' => 'effective_at',
            'label'  => 'Fecha efectiva',
        ],

        [
            'label'  => 'Referencia',
        ],
        [
            'column' => 'total_cost',
            'label'  => 'Costo MXN',
            'align'  => 'right',
        ],
        [
            'label' => 'Responsable',
        ],
        [
            'label' => 'Creado por',
        ],
        [
            'column' => 'created_at',
            'label'  => 'Creado el',
        ],
        [
            'label' => 'Ver más',
            'align' => 'center',
        ],
    ];

    public function render(): View
    {
        $documents = $this->getQuery()->paginate($this->perPage);

        return view(
            'livewire.inventory.raw-material-documents.documents-table',
            [
                'documents' => $documents,
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
        $query = RawMaterialDocument::query()
            ->with([
                'responsible:id,name',
                'creator:id,name'
            ]);

        if ($term = $this->searchTerm) {
            $query->where(function (Builder $q) use ($term) {
                $q->where('description', 'like', "%{$term}%")
                    ->orWhere('reference_number', 'like', "%{$term}%");
            });
        }

        $query->orderBy($this->sortColumn, $this->sortDirection);

        return $query;
    }
}
