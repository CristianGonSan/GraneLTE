<?php

namespace App\View\Components\Inventory\Dashboard;

use App\Enums\Inventory\RawMaterialDocument\RawMaterialDocumentStatus;
use App\Models\Inventory\RawMaterialDocument;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;
use Illuminate\View\View;

class PendingDocumentsTable extends Component
{
    /** @var Collection<int, RawMaterialDocument> */
    public Collection $documents;

    public function __construct()
    {
        $this->documents = $this->resolveDocuments();
    }

    public function render(): View
    {
        return view('components.inventory.dashboard.pending-documents-table');
    }

    /** @return Collection<int, RawMaterialDocument> */
    private function resolveDocuments(): Collection
    {
        return RawMaterialDocument::where('status', RawMaterialDocumentStatus::PENDING)
            ->with([
                'creator:id,name',
                'responsible:id,name',
            ])
            ->latest()
            ->limit(10)
            ->get();
    }
}
