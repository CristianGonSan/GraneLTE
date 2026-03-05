<?php

namespace App\Livewire\Inventory\Warehouses;

use App\Exports\Excel\Inventory\WarehouseValuationExport;
use Illuminate\View\View;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportWarehouseValuation extends Component
{
    // — Filtros —
    public bool $onlyWithStock = true;

    // — Ordenamiento —
    public string $orderBy        = 'warehouse';
    public string $orderDirection = 'asc';

    public function render(): View
    {
        return view('livewire.inventory.warehouses.export-warehouse-valuation', [
            'sortableColumns' => WarehouseValuationExport::sortableColumns(),
        ]);
    }

    public function export(): BinaryFileResponse
    {
        return Excel::download(
            new WarehouseValuationExport(
                onlyWithStock: $this->onlyWithStock,
                orderBy: $this->orderBy,
                orderDirection: $this->orderDirection,
            ),
            'valorizacion_almacenes_' . now()->format('Y-m-d') . '.xlsx'
        );
    }
}
