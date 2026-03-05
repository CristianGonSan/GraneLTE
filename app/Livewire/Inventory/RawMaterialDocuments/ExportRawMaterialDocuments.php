<?php

namespace App\Livewire\Inventory\RawMaterialDocuments;

use App\Enums\Inventory\RawMaterialDocument\RawMaterialDocumentStatus;
use App\Enums\Inventory\RawMaterialDocument\RawMaterialDocumentType;
use App\Exports\Excel\Inventory\RawMaterialDocumentsExport;
use App\Models\Inventory\RawMaterialDocument;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportRawMaterialDocuments extends Component
{
    // — Filtros de tipo y estado —
    public string $documentType   = '';
    public string $documentStatus = '';

    // — Filtros de identificación —
    public ?int $responsibleId = null;
    public ?int $createdById   = null;
    public ?int $supplierId    = null;

    // — Filtros de fechas efectivas —
    public ?string $effectiveFrom = null;
    public ?string $effectiveTo   = null;

    // — Filtros de fechas de validación —
    public ?string $validatedFrom = null;
    public ?string $validatedTo   = null;

    // — Ordenamiento —
    public string $orderBy        = 'effective_at';
    public string $orderDirection = 'desc';

    public function render(): View
    {
        return view('livewire.inventory.raw-material-documents.export-raw-material-documents', [
            'documentTypes'   => RawMaterialDocumentType::options(),
            'documentStatuses' => RawMaterialDocumentStatus::options(),
            'sortableColumns' => $this->sortableColumns(),
        ]);
    }

    public function export(): BinaryFileResponse
    {
        $query = $this->buildQuery();

        $this->applyOrder($query);

        return Excel::download(
            new RawMaterialDocumentsExport($query),
            'documentos_' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    /**
     * @return Builder<RawMaterialDocument>
     */
    private function buildQuery(): Builder
    {
        return RawMaterialDocument::query()
            ->from('raw_material_documents as documents')
            ->leftJoin('responsibles', 'responsibles.id', '=', 'documents.responsible_id')
            ->leftJoin('users as creators', 'creators.id', '=', 'documents.created_by')
            ->leftJoin('raw_material_receipts as receipts', 'receipts.document_id', '=', 'documents.id')
            ->select('documents.*')
            ->when(
                $this->documentType !== '' || $this->supplierId !== null,
                fn(Builder $q) => $q->where('documents.type', $this->supplierId !== null ? RawMaterialDocumentType::RECEIPT->value : $this->documentType)
            )
            ->when($this->documentStatus !== '', fn(Builder $q) => $q->where('documents.status', $this->documentStatus))
            ->when($this->responsibleId,         fn(Builder $q) => $q->where('documents.responsible_id', $this->responsibleId))
            ->when($this->createdById,           fn(Builder $q) => $q->where('documents.created_by', $this->createdById))
            ->when($this->supplierId,            fn(Builder $q) => $q->where('receipts.supplier_id', $this->supplierId))
            ->when($this->effectiveFrom,         fn(Builder $q) => $q->where('documents.effective_at', '>=', $this->effectiveFrom))
            ->when($this->effectiveTo,           fn(Builder $q) => $q->where('documents.effective_at', '<=', $this->effectiveTo))
            ->when($this->validatedFrom,         fn(Builder $q) => $q->where('documents.validated_at', '>=', $this->validatedFrom))
            ->when($this->validatedTo,           fn(Builder $q) => $q->where('documents.validated_at', '<=', $this->validatedTo));
    }

    /**
     * @param Builder<RawMaterialDocument> $query
     */
    private function applyOrder(Builder $query): void
    {
        $dir = $this->sanitizedDirection();

        if ($this->orderBy === 'type') {
            $cases = collect(RawMaterialDocumentType::cases())
                ->map(fn(RawMaterialDocumentType $case): string => "WHEN '{$case->value}' THEN '{$case->label()}'")
                ->implode(' ');
            $query->orderByRaw("CASE documents.type $cases END $dir");
        } elseif ($this->orderBy === 'status') {
            $cases = collect(RawMaterialDocumentStatus::cases())
                ->map(fn(RawMaterialDocumentStatus $case): string => "WHEN '{$case->value}' THEN '{$case->label()}'")
                ->implode(' ');
            $query->orderByRaw("CASE documents.status $cases END $dir");
        } else {
            $query->orderBy($this->resolveOrderColumn($this->orderBy), $dir);
        }
    }

    /**
     * @return array<string, string>
     */
    public function sortableColumns(): array
    {
        return [
            'effective_at'  => 'Fecha efectiva',
            'type'          => 'Tipo',
            'status'        => 'Estado',
            'total_cost'    => 'Costo total',
            'responsible'   => 'Responsable',
            'creator'       => 'Creado por',
            'validated_at'  => 'Fecha validación',
        ];
    }

    private function sanitizedDirection(): string
    {
        return $this->orderDirection === 'desc' ? 'desc' : 'asc';
    }

    private function resolveOrderColumn(string $key): string
    {
        return match ($key) {
            'effective_at'  => 'documents.effective_at',
            'total_cost'    => 'documents.total_cost',
            'responsible'   => 'responsibles.name',
            'creator'       => 'creators.name',
            'validated_at'  => 'documents.validated_at',
            default         => 'documents.effective_at',
        };
    }
}
