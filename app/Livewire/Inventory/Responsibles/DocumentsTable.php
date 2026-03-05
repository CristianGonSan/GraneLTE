<?php

namespace App\Livewire\Inventory\Responsibles;

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
    public int $responsibleId;

    public string $searchTerm = '';

    public int $perPage = 12;

    public int $page = 1;

    public string $sortColumn = 'id';

    public string $sortDirection = 'desc';

    public string $type = 'all';

    public string $status = 'all';

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

    public function mount(int $responsibleId): void
    {
        $this->responsibleId = $responsibleId;
        $this->setPage($this->page);
    }

    public function render(): View
    {
        $documents = $this->getQuery()->paginate($this->perPage);

        return view('livewire.inventory.responsibles.documents-table', [
            'documents'     => $documents,
            'typeOptions'   => RawMaterialDocumentType::options(),
            'statusOptions' => RawMaterialDocumentStatus::options(),
        ]);
    }

    private function getQuery(): Builder
    {
        $query = RawMaterialDocument::query()
            ->from('raw_material_documents as documents')
            ->leftJoin('users', 'documents.created_by', '=', 'users.id')
            ->with([
                'creator:id,name',
            ])
            ->select('documents.*');

        $query->whereResponsibleId($this->responsibleId);

        if ($this->type !== 'all') {
            $query->whereType($this->type);
        }

        if ($this->status !== 'all') {
            $query->whereStatus($this->status);
        }

        if ($term = $this->searchTerm) {
            $query->where(function ($q) use ($term) {
                $q->where('documents.reference_number', 'like', "%$term%")
                    ->orWhere('users.name', 'like', "%$term%");
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
