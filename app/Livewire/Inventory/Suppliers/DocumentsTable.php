<?php

namespace App\Livewire\Inventory\Suppliers;

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
    public int $supplierId;

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

    public function mount(int $supplierId): void
    {
        $this->supplierId = $supplierId;
        $this->setPage($this->page);
    }

    public function render(): View
    {
        $documents = $this->getQuery()->paginate($this->perPage);

        return view('livewire.inventory.suppliers.documents-table', [
            'documents'     => $documents,
            'statusOptions' => RawMaterialDocumentStatus::options(),
        ]);
    }

    private function getQuery(): Builder
    {
        $query = RawMaterialDocument::query()
            ->from('raw_material_documents as documents')
            ->leftJoin('responsibles', 'documents.responsible_id', '=', 'responsibles.id')
            ->leftJoin('users', 'documents.created_by', '=', 'users.id')
            ->leftJoin('raw_material_receipts as receipts', 'documents.id', '=', 'receipts.document_id')
            ->leftJoin('suppliers', 'receipts.supplier_id', '=', 'suppliers.id')
            ->with([
                'responsible:id,name',
                'creator:id,name',
            ])
            ->select('documents.*');

        $query->where('suppliers.id', '=', $this->supplierId);

        if ($this->type !== 'all') {
            $query->whereType($this->type);
        }

        if ($this->status !== 'all') {
            $query->whereStatus($this->status);
        }

        if ($term = $this->searchTerm) {
            $query->where(function ($q) use ($term) {
                $q->where('documents.reference_number', 'like', "%$term%")
                    ->orWhere('responsibles.name', 'like', "%$term%")
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
