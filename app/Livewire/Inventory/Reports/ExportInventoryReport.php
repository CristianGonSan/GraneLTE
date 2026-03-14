<?php

namespace App\Livewire\Inventory\Reports;

use App\Exports\Excel\Inventory\Reports\InventoryReportExport;
use Carbon\Carbon;
use Illuminate\View\View;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportInventoryReport extends Component
{
    // — Rango de fechas para movimientos —
    public ?string $movementsFrom = null;
    public ?string $movementsTo   = null;

    // — Dias para lotes por vencer —
    public int $expiringDays = 30;

    public function mount(): void
    {
        $this->movementsFrom = now()->subDays(30)->format('Y-m-d\TH:i');
        $this->movementsTo   = now()->format('Y-m-d\TH:i');
    }

    public function render(): View
    {
        return view('livewire.inventory.reports.export-inventory-report');
    }

    public function export(): BinaryFileResponse
    {
        $this->validate([
            'movementsFrom' => ['nullable', 'date'],
            'movementsTo'   => ['nullable', 'date', 'after_or_equal:movementsFrom'],
            'expiringDays'  => ['required', 'integer', 'min:1', 'max:365'],
        ]);

        return Excel::download(
            new InventoryReportExport(
                movementsFrom: $this->movementsFrom
                    ? Carbon::parse($this->movementsFrom)
                    : now()->subDays(30),
                movementsTo: $this->movementsTo
                    ? Carbon::parse($this->movementsTo)
                    : now(),
                expiringDays: $this->expiringDays,
            ),
            'reporte-inventario-' . now()->format('Y-m-d') . '.xlsx'
        );
    }
}
