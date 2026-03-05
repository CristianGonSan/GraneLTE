<?php

namespace App\Livewire\Inventory\RawMaterials;

use App\Models\Inventory\RawMaterial;
use App\Traits\Livewire\Tables\HasLivewireTableBehavior;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Session;
use Livewire\Component;

class RawMaterialsTable extends Component
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

    #[Session]
    public array $filters = [
        'quantityMin'  => null,
        'quantityMax'  => null,
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
            'column' => 'category',
            'label'  => 'Categoría',
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

    public function mount(): void
    {
        $this->setPage($this->page);
    }

    public function render(): View
    {
        $materials = $this->getQuery()->paginate($this->perPage);

        return view('livewire.inventory.raw-materials.raw-materials-table', [
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
            ->leftJoin('categories', 'raw_materials.category_id', '=', 'categories.id')
            ->with([
                'unit:id,symbol',
                'category:id,name',
            ])
            ->select('raw_materials.*');

        if ($this->filters['quantityMin'] !== null) {
            $query->where('raw_materials.current_quantity', '>=', $this->filters['quantityMin']);
        }

        if ($this->filters['quantityMax'] !== null) {
            $query->where('raw_materials.current_quantity', '<=', $this->filters['quantityMax']);
        }

        match ($this->filters['lowStockFilter']) {
            'low_stock' => $query
                ->whereColumn('raw_materials.current_quantity', '<', 'raw_materials.minimum_stock')
                ->where('raw_materials.minimum_stock', '>', 0),
            'ok' => $query->where(function (Builder $q): void {
                $q->whereColumn('raw_materials.current_quantity', '>=', 'raw_materials.minimum_stock')
                    ->orWhere('raw_materials.minimum_stock', '<=', 0);
            }),
            default => null,
        };

        if ($term = $this->searchTerm) {
            $query->where(function (Builder $q) use ($term): void {
                $q->where('raw_materials.name', 'like', "%$term%")
                    ->orWhere('raw_materials.abbreviation', 'like', "$term%")
                    ->orWhere('categories.name', 'like', "%$term%");
            });
        }

        $sortable = [
            'name'             => 'raw_materials.name',
            'abbreviation'     => 'raw_materials.abbreviation',
            'current_quantity' => 'raw_materials.current_quantity',
            'is_active'        => 'raw_materials.is_active',
            'category'         => 'categories.name',
        ];

        $column = $sortable[$this->sortColumn] ?? 'raw_materials.name';

        $query->orderBy($column, $this->sortDirection);

        return $query;
    }
}
