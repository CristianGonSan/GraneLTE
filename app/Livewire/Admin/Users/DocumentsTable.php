<?php

namespace App\Livewire\Admin\Users;

use App\Enums\Inventory\RawMaterialDocument\RawMaterialDocumentStatus;
use App\Enums\Inventory\RawMaterialDocument\RawMaterialDocumentType;
use App\Models\Inventory\RawMaterialDocument;
use App\Traits\Livewire\Tables\HasLivewireTableBehavior;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Locked;
use Livewire\Component;

class DocumentsTable extends Component
{
    use HasLivewireTableBehavior;

    #[Locked]
    public int $userId;

    public string $searchTerm = '';

    public int $perPage = 12;

    public int $page = 1;

    public string $sortColumn = 'id';

    public string $sortDirection = 'desc';

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

    public function mount(int $userId): void
    {
        $this->userId = $userId;
        $this->setPage($this->page);
    }

    public function render(): View
    {
        $documents = $this->getQuery()->paginate($this->perPage);

        return view('livewire.admin.users.documents-table', [
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
            ->with([
                'responsible:id,name',
            ])
            ->select('documents.*');

        $query->whereCreatedBy($this->userId);

        if ($this->filters['type'] !== 'all') {
            $query->whereType($this->filters['type']);
        }

        if ($this->filters['status'] !== 'all') {
            $query->whereStatus($this->filters['status']);
        }

        if ($term = $this->searchTerm) {
            $query->where(function ($q) use ($term) {
                $q->where('documents.reference_number', 'like', "%$term%")
                    ->orWhere('responsibles.name', 'like', "%$term%");
            });
        }

        if ($this->sortColumn === 'type') {
            $cases = collect(RawMaterialDocumentType::cases())
                ->map(fn($case) => "WHEN '{$case->value}' THEN '{$case->label()}'")
                ->implode(' ');

            $query->orderByRaw("CASE documents.type $cases END {$this->sortDirection}");

            return $query;
        }

        if ($this->sortColumn === 'status') {
            $cases = collect(RawMaterialDocumentStatus::cases())
                ->map(fn($case) => "WHEN '{$case->value}' THEN '{$case->label()}'")
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
        ];

        $column = $sortable[$this->sortColumn] ?? 'documents.id';

        $query->orderBy($column, $this->sortDirection);

        return $query;
    }
}
