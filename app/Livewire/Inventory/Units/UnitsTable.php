<?php

namespace App\Livewire\Inventory\Units;

use App\Models\Inventory\Unit;
use App\Traits\Livewire\Tables\HasLivewireTableBehavior;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Session;
use Livewire\Component;

class UnitsTable extends Component
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
        ],
        [
            'column' => 'symbol',
            'label'  => 'Símbolo',
            'align'  => 'center',
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
        $units = $this->getQuery()->paginate($this->perPage);

        return view('livewire.inventory.units.units-table', [
            'units' => $units,
        ]);
    }

    private function getQuery(): Builder
    {
        $query = Unit::query();

        if ($term = $this->searchTerm) {
            $query->where(function ($q) use ($term) {
                $q->where('symbol', 'like', "$term%")
                    ->orWhere('name', 'like', "%$term%");
            });
        }

        $query->orderBy($this->sortColumn, $this->sortDirection);

        return $query;
    }
}
