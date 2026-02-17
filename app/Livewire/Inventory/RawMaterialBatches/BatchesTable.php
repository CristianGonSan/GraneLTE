<?php

namespace App\Livewire\Inventory\RawMaterialBatches;

use App\Models\Inventory\RawMaterialBatch;
use App\Traits\Livewire\WithTableSorting;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Session;

class BatchesTable extends Component
{
    use WithPagination, WithTableSorting;

    #[Session]
    public string $searchTerm = '';

    #[Session]
    public int $perPage = 12;

    #[Session]
    public int $page = 1;

    #[Session]
    public string $sortColumn = 'received_at';

    #[Session]
    public string $sortDirection = 'desc';

    protected array $theadConfig = [
        [
            'column' => 'batch_code',
            'label'  => 'Codigo',
        ],
        [
            'column' => 'external_batch_code',
            'label'  => 'Codigo externo',
        ],
        [
            'column' => 'current_quantity',
            'label'  => 'Disponible',
            'align'  => 'center',
        ],
        [
            'label'  => 'Valor actual MXN',
            'align'  => 'center',
        ],
        [
            'column' => 'received_at',
            'label'  => 'Recepción',
        ],
        [
            'column' => 'expiration_date',
            'label'  => 'Caducidad',
        ],
        [
            'label'  => 'Ver más',
            'align'  => 'center',
        ],
    ];

    public function render(): View
    {
        $batches = $this->getQuery()->paginate($this->perPage);

        return view(
            'livewire.inventory.raw-material-batches.batches-table',
            [
                'batches' => $batches,
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
        $query = RawMaterialBatch::query()
            ->with([
                'material:id,name,unit_id',
                'supplier:id,name',
            ]);

        if ($term = $this->searchTerm) {
            $query->where(function (Builder $q) use ($term) {
                $q->where('batch_code', 'like', "%{$term}%")
                    ->orWhere('external_batch_code', 'like', "%{$term}%");
            });
        }

        $query->orderBy($this->sortColumn, $this->sortDirection);

        return $query;
    }
}
