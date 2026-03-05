<?php

namespace App\Livewire\Inventory\RawMaterialBatches;

use App\Models\Inventory\RawMaterialBatch;
use App\Traits\Livewire\Tables\HasLivewireTableBehavior;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Session;
use Livewire\Component;

class BatchesTable extends Component
{
    use HasLivewireTableBehavior;

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

    #[Session]
    public array $filters = [
        'quantityMin'      => 0.001,
        'quantityMax'      => null,
        'expirationFilter' => 'all',
        'expirationDays'   => 30,
    ];

    protected array $theadConfig = [
        [
            'column' => 'code',
            'label'  => 'Código',
        ],
        [
            'column' => 'material',
            'label'  => 'Material',
        ],
        [
            'column' => 'supplier',
            'label'  => 'Proveedor',
        ],
        [
            'column' => 'current_quantity',
            'label'  => 'En stock',
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
        $batches = $this->getQuery()->paginate($this->perPage);

        return view('livewire.inventory.raw-material-batches.batches-table', [
            'batches' => $batches,
        ]);
    }

    public function updatedFilters(mixed $value, string $key): void
    {
        if ($value === '') {
            $this->filters[$key] = null;
        }

        if ($key === 'expirationDays' && $this->filters['expirationDays'] < 1) {
            $this->filters['expirationDays'] = 1;
        }

        $this->resetPage();
    }

    private function getQuery(): Builder
    {
        $query = RawMaterialBatch::query()
            ->from('raw_material_batches as batches')
            ->leftJoin('raw_materials as materials', 'batches.material_id', '=', 'materials.id')
            ->leftJoin('suppliers', 'batches.supplier_id', '=', 'suppliers.id')
            ->with([
                'material:id,name,unit_id',
                'material.unit:id,symbol',
                'supplier:id,name',
            ])
            ->select('batches.*');

        if ($this->filters['quantityMin'] !== null) {
            $query->where('batches.current_quantity', '>=', $this->filters['quantityMin']);
        }

        if ($this->filters['quantityMax'] !== null) {
            $query->where('batches.current_quantity', '<=', $this->filters['quantityMax']);
        }

        match ($this->filters['expirationFilter']) {
            'not_expired'    => $query->where(fn(Builder $q) => $q
                ->whereNull('batches.expiration_date')
                ->orWhere('batches.expiration_date', '>', now())),
            'expiring'       => $query->whereNotNull('batches.expiration_date')
                ->where('batches.expiration_date', '>', now())
                ->where('batches.expiration_date', '<=', now()->addDays($this->filters['expirationDays']))
                ->orderBy('batches.expiration_date', 'asc'),
            'expired'        => $query->whereNotNull('batches.expiration_date')
                ->where('batches.expiration_date', '<=', now()),
            'non_perishable' => $query->whereNull('batches.expiration_date'),
            default          => null,
        };

        if ($term = $this->searchTerm) {
            $query->where(function (Builder $q) use ($term): void {
                $q->where('batches.external_batch_code', 'like', "%$term%")
                    ->orWhere('batches.batch_code', 'like', "%$term%")
                    ->orWhere('materials.name', 'like', "%$term%")
                    ->orWhere('suppliers.name', 'like', "%$term%");
            });
        }

        if ($this->sortColumn === 'code') {
            $query->orderByRaw("COALESCE(batches.external_batch_code, batches.batch_code) {$this->sortDirection}");

            return $query;
        }

        if ($this->sortColumn === 'expiration_date') {
            $query->orderByRaw("batches.expiration_date IS NULL")
                ->orderBy('batches.expiration_date', $this->sortDirection);

            return $query;
        }

        $sortable = [
            'received_at'      => 'batches.received_at',
            'current_quantity' => 'batches.current_quantity',
            'material'         => 'materials.name',
            'supplier'         => 'suppliers.name',
        ];

        $column = $sortable[$this->sortColumn] ?? 'batches.received_at';

        $query->orderBy($column, $this->sortDirection);

        return $query;
    }
}
