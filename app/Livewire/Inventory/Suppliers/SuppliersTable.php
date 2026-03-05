<?php

namespace App\Livewire\Inventory\Suppliers;

use App\Models\Inventory\Supplier;
use App\Traits\Livewire\Tables\HasLivewireTableBehavior;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Session;
use Livewire\Component;

class SuppliersTable extends Component
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
            'column' => 'contact_person',
            'label'  => 'Persona de Contacto',
        ],
        [
            'label' => 'Email',
        ],
        [
            'label' => 'Teléfono',
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
        $suppliers = $this->getQuery()->paginate($this->perPage);

        return view('livewire.inventory.suppliers.suppliers-table', [
            'suppliers' => $suppliers,
        ]);
    }

    private function getQuery(): Builder
    {
        $query = Supplier::query();

        if ($term = $this->searchTerm) {
            $query->where(function ($q) use ($term) {
                $q->whereAny(
                    ['name', 'contact_person', 'email'],
                    'like',
                    "%$term%"
                )->orWhere('phone', 'like', "$term%");
            });
        }

        if ($this->sortColumn === 'contact_person') {
            $query->orderByRaw("contact_person IS NULL")
                ->orderBy('contact_person', $this->sortDirection);

            return $query;
        }

        $query->orderBy($this->sortColumn, $this->sortDirection);

        return $query;
    }
}
