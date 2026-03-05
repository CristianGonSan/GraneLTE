<?php

namespace App\Livewire\Inventory\Categories;

use App\Exports\Excel\Inventory\CategoryValuationExport;
use Illuminate\View\View;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportCategoryValuation extends Component
{
    // — Filtros —
    public bool $onlyWithStock = true;

    // — Ordenamiento —
    public string $orderBy        = 'category';
    public string $orderDirection = 'asc';

    public function render(): View
    {
        return view('livewire.inventory.categories.export-category-valuation', [
            'sortableColumns' => CategoryValuationExport::sortableColumns(),
        ]);
    }

    public function export(): BinaryFileResponse
    {
        return Excel::download(
            new CategoryValuationExport(
                onlyWithStock: $this->onlyWithStock,
                orderBy: $this->orderBy,
                orderDirection: $this->orderDirection,
            ),
            'valorizacion_categorias_' . now()->format('Y-m-d') . '.xlsx'
        );
    }
}
