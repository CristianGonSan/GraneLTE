<?php

namespace App\Livewire\Inventory\RawMaterialDocuments;

use App\Enums\Inventory\RawMaterialDocument\RawMaterialDocumentStatus;
use App\Enums\Inventory\RawMaterialDocument\RawMaterialDocumentType;
use App\Models\Inventory\RawMaterialDocument;
use App\Traits\Livewire\Tables\HasLivewireTableBehavior;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Session;
use Livewire\Component;

class DocumentsTable extends Component
{
    use HasLivewireTableBehavior;

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

    #[Session]
    public array $filters = [
        'type'   => 'all',
        'status' => 'all',
    ];

    protected array $theadConfig = [
        [
            'column' => 'id',
            'label'  => 'ID',
            'align'  => 'center',
            'style'  => 'width: 1%;',
        ],
        [
            'column' => 'reference_number',
            'label'  => 'Referencia',
        ],
        [
            'column' => 'type',
            'label'  => 'Tipo',
        ],
        [
            'column' => 'status',
            'label'  => 'Estado',
        ],
        [
            'column' => 'responsible',
            'label'  => 'Responsable',
        ],
        [
            'column' => 'creator',
            'label'  => 'Creador',
        ],
        [
            'column' => 'total_cost',
            'label'  => 'Costo MXN',
        ],
        [
            'column' => 'effective_at',
            'label'  => 'Fecha efectiva',
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

    public function mount(): void
    {
        if (request()->has('status')) {
            $status = RawMaterialDocumentStatus::tryFrom(request()->query('status'));

            if ($status !== null) {
                $this->filters['status'] = $status->value;
            }
        }
        
        $this->setPage($this->page);
    }

    public function render(): View
    {
        $documents = $this->getQuery()->paginate($this->perPage);

        return view('livewire.inventory.raw-material-documents.documents-table', [
            'documents'     => $documents,
            'typeOptions'   => RawMaterialDocumentType::options(),
            'statusOptions' => RawMaterialDocumentStatus::options(),
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
        $query = RawMaterialDocument::query()
            ->from('raw_material_documents as documents')
            ->leftJoin('responsibles', 'documents.responsible_id', '=', 'responsibles.id')
            ->leftJoin('users', 'documents.created_by', '=', 'users.id')
            ->with([
                'responsible:id,name',
                'creator:id,name',
            ])
            ->select('documents.*');

        if ($this->filters['type'] !== 'all') {
            $query->whereType($this->filters['type']);
        }

        if ($this->filters['status'] !== 'all') {
            $query->whereStatus($this->filters['status']);
        }

        if ($term = $this->searchTerm) {
            $query->where(function (Builder $q) use ($term): void {
                $q->where('documents.reference_number', 'like', "%$term%")
                    ->orWhere('responsibles.name', 'like', "%$term%")
                    ->orWhere('users.name', 'like', "%$term%");
            });
        }

        if ($this->sortColumn === 'type') {
            $cases = collect(RawMaterialDocumentType::cases())
                ->map(fn(RawMaterialDocumentType $case) => "WHEN '{$case->value}' THEN '{$case->label()}'")
                ->implode(' ');

            $query->orderByRaw("CASE documents.type $cases END {$this->sortDirection}");

            return $query;
        }

        if ($this->sortColumn === 'status') {
            $cases = collect(RawMaterialDocumentStatus::cases())
                ->map(fn(RawMaterialDocumentStatus $case) => "WHEN '{$case->value}' THEN '{$case->label()}'")
                ->implode(' ');

            $query->orderByRaw("CASE documents.status $cases END {$this->sortDirection}");

            return $query;
        }

        if ($this->sortColumn === 'reference_number') {
            $query->orderByRaw("documents.reference_number IS NULL")
                ->orderBy('documents.reference_number', $this->sortDirection);

            return $query;
        }

        if ($this->sortColumn === 'responsible') {
            $query->orderByRaw("responsibles.name IS NULL")
                ->orderBy('responsibles.name', $this->sortDirection);

            return $query;
        }

        $sortable = [
            'id'           => 'documents.id',
            'total_cost'   => 'documents.total_cost',
            'effective_at' => 'documents.effective_at',
            'created_at'   => 'documents.created_at',
            'creator'      => 'users.name',
        ];

        $column = $sortable[$this->sortColumn] ?? 'documents.id';

        $query->orderBy($column, $this->sortDirection);

        return $query;
    }
}
