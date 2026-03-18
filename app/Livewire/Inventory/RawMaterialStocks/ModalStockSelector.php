<?php

namespace App\Livewire\Inventory\RawMaterialStocks;

use App\Models\Inventory\RawMaterialStock;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class ModalStockSelector extends Component
{
    use WithPagination, WithoutUrlPagination;

    #[Locked]
    public bool $closeAfterSelected;

    public bool $showModal = false;

    public string $searchTerm = '';

    public array $filters = [
        'order'            => 'fefo',
        'quantityMin'      => 0.001,
        'quantityMax'      => null,
        'expirationFilter' => 'all',
        'expirationDays'   => 30,
    ];

    public function mount(bool $closeAfterSelected = false): void
    {
        $this->closeAfterSelected = $closeAfterSelected;
    }

    public function render(): View
    {
        $stocks = $this->getQuery()->paginate();

        return view('livewire.inventory.raw-material-stocks.modal-stock-selector', [
            'stocks' => $stocks,
        ]);
    }

    #[On('openStockSelector')]
    public function openModal(): void
    {
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
    }

    public function search(): void
    {
        $this->resetPage();
    }

    public function clearSearch(): void
    {
        $this->searchTerm = '';
        $this->resetPage();
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
        $query = RawMaterialStock::query()
            ->from('raw_material_stocks as stocks')
            ->join('raw_material_batches as batches', 'batches.id', '=', 'stocks.batch_id')
            ->join('raw_materials as materials', 'materials.id', '=', 'batches.material_id')
            ->join('warehouses', 'warehouses.id', '=', 'stocks.warehouse_id')
            ->select('stocks.*')
            ->with([
                'batch.material.unit',
                'warehouse',
            ]);

        // Filtros de cantidad (ahora tienen sentido directo)
        if ($this->filters['quantityMin'] !== null) {
            $query->where('stocks.current_quantity', '>=', $this->filters['quantityMin']);
        }

        if ($this->filters['quantityMax'] !== null) {
            $query->where('stocks.current_quantity', '<=', $this->filters['quantityMax']);
        }

        // Filtros de expiración (siguen dependiendo del batch)
        match ($this->filters['expirationFilter']) {
            'not_expired' => $query->where(
                fn(Builder $q) => $q
                    ->whereNull('batches.expiration_date')
                    ->orWhere('batches.expiration_date', '>', now())
            ),

            'expiring' => $query->whereNotNull('batches.expiration_date')
                ->where('batches.expiration_date', '>', now())
                ->where('batches.expiration_date', '<=', now()->addDays($this->filters['expirationDays']))
                ->orderBy('batches.expiration_date', 'asc'),

            'expired' => $query->whereNotNull('batches.expiration_date')
                ->where('batches.expiration_date', '<=', now()),

            'non_perishable' => $query->whereNull('batches.expiration_date'),

            default => null,
        };

        // Búsqueda
        if ($term = $this->searchTerm) {
            $query->where(function (Builder $q) use ($term) {
                $q->where('materials.name', 'like', "%{$term}%")
                    ->orWhere('warehouses.name', 'like', "%{$term}%")
                    ->orWhere('batches.external_batch_code', 'like', "%{$term}%")
                    ->orWhere('batches.batch_code', 'like', "%{$term}%");
            });
        }

        // Ordenamiento
        switch ($this->filters['order']) {
            case 'fifo':
                $query->orderBy('batches.created_at', 'asc');
                break;

            case 'fefo':
                $query->orderByRaw("batches.expiration_date IS NULL")
                    ->orderBy('batches.expiration_date');
                break;

            case 'lifo':
                $query->orderBy('batches.created_at', 'desc');
                break;

            case 'stock':
                $query->orderBy('stocks.current_quantity', 'desc');
                break;
        }

        return $query;
    }
}
