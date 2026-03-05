<?php

namespace App\Livewire\Inventory\Categories;

use App\Models\Inventory\RawMaterial;
use App\Traits\Livewire\Tables\HasLivewireTableBehavior;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Locked;
use Livewire\Component;

class RawMaterialsTable extends Component
{
    use HasLivewireTableBehavior;

    #[Locked]
    public int $categoryId;

    public string $searchTerm = '';

    public int $perPage = 12;

    public int $page = 1;

    public string $sortColumn = 'name';

    public string $sortDirection = 'desc';

    public array $filters = [
        'quantityMin'    => null,
        'quantityMax'    => null,
        'lowStockFilter' => 'all',
    ];

    protected array $theadConfig = [
        [
            'column' => 'name',
            'label'  => 'Nombre',
        ],
        [
            'column' => 'abbreviation',
            'label'  => 'Abreviatura',
        ],
        [
            'column' => 'current_quantity',
            'label'  => 'En stock',
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

    public function mount(int $categoryId): void
    {
        $this->categoryId = $categoryId;
        $this->setPage($this->page);
    }

    public function render(): View
    {
        $materials = $this->getQuery()->paginate($this->perPage);

        return view('livewire.inventory.categories.raw-materials-table', [
            'materials' => $materials,
        ]);
    }

    public function updatedFilters(mixed $value, string $key): void
    {
        if ($value === '') {
            $this->filters[$key] = null;
        }

        $this->resetPage();
    }

    private function getQuery(): Builder
    {
        $query = RawMaterial::query()
            ->with(['unit:id,symbol'])
            ->whereCategoryId($this->categoryId);

        if ($this->filters['quantityMin'] !== null) {
            $query->where('current_quantity', '>=', $this->filters['quantityMin']);
        }

        if ($this->filters['quantityMax'] !== null) {
            $query->where('current_quantity', '<=', $this->filters['quantityMax']);
        }

        match ($this->filters['lowStockFilter']) {
            'low_stock' => $query
                ->whereColumn('current_quantity', '<', 'minimum_stock')
                ->where('minimum_stock', '>', 0),
            'ok' => $query->where(function (Builder $q): void {
                $q->whereColumn('current_quantity', '>=', 'minimum_stock')
                    ->orWhere('minimum_stock', '<=', 0);
            }),
            default => null,
        };

        if ($term = $this->searchTerm) {
            $query->where(function (Builder $q) use ($term): void {
                $q->where('name', 'like', "%$term%")
                    ->orWhere('abbreviation', 'like', "$term%");
            });
        }

        $query->orderBy($this->sortColumn, $this->sortDirection);

        return $query;
    }
}
