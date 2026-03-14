<?php

namespace App\Livewire\Inventory\RawMaterials;

use App\Models\Inventory\RawMaterial;
use App\Models\Inventory\RawMaterialBatch;
use App\Traits\Livewire\Tables\HasLivewireTableBehavior;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Locked;
use Livewire\Component;

class BatchesTable extends Component
{
    use HasLivewireTableBehavior;

    #[Locked]
    public int $rawMaterialId;

    public string $unitSymbol;

    public string $searchTerm = '';

    public int $perPage = 12;

    public int $page = 1;

    public string $sortColumn = 'received_at';

    public string $sortDirection = 'desc';

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
            'column' => 'supplier',
            'label'  => 'Proveedor',
        ],
        [
            'column' => 'current_quantity',
            'label'  => 'En Stock',
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

    public function mount(int $rawMaterialId): void
    {
        $material = RawMaterial::findOrFail($rawMaterialId, ['id', 'unit_id']);
        $this->rawMaterialId = $rawMaterialId;
        $this->unitSymbol    = $material->unit->symbol;

        $this->setPage($this->page);
    }

    public function render(): View
    {
        $batches = $this->getQuery()->paginate($this->perPage);

        return view('livewire.inventory.raw-materials.batches-table', [
            'batches' => $batches,
        ]);
    }

    public function updatedFilters(mixed $value, string $key): void
    {
        if ($value === '') {
            $this->filters[$key] = null;
        }

        if ($key === 'expirationDays') {
            $this->filters['expirationDays'] = max(1, (int) $this->filters['expirationDays']);
        }

        $this->resetPage();
    }

    private function getQuery(): Builder
    {
        $query = RawMaterialBatch::query()
            ->from('raw_material_batches as batches')
            ->leftJoin('suppliers', 'batches.supplier_id', '=', 'suppliers.id')
            ->with([
                'supplier:id,name',
            ])
            ->select('batches.*');

        $query->where('batches.material_id', '=', $this->rawMaterialId);

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
            'supplier'         => 'suppliers.name',
        ];

        $column = $sortable[$this->sortColumn] ?? 'batches.received_at';

        $query->orderBy($column, $this->sortDirection);

        return $query;
    }
}
