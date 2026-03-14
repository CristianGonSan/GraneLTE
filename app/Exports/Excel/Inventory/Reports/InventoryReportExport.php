<?php

namespace App\Exports\Excel\Inventory\Reports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class InventoryReportExport implements WithMultipleSheets
{
    public function __construct(
        private readonly Carbon $movementsFrom,
        private readonly Carbon $movementsTo,
        private readonly int    $expiringDays = 30,
    ) {}

    /**
     * @return array<int, SummarySheet|LowStockSheet|ExpiringBatchesSheet|StockByWarehouseSheet|RecentMovementsSheet|AnomaliesSheet>
     */
    public function sheets(): array
    {
        return [
            new SummarySheet(),
            new LowStockSheet(),
            new ExpiringBatchesSheet($this->expiringDays),
            new StockByWarehouseSheet(),
            new RecentMovementsSheet($this->movementsFrom, $this->movementsTo),
            new AnomaliesSheet(),
        ];
    }
}
