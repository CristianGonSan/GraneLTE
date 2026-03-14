<?php

namespace App\View\Components\Inventory\Dashboard;

use App\Enums\Inventory\RawMaterialDocument\RawMaterialDocumentStatus;
use App\Enums\Inventory\RawMaterialDocument\RawMaterialDocumentType;
use App\Models\Inventory\RawMaterialDocument;
use Illuminate\View\Component;
use Illuminate\View\View;

class DocumentCostInfoBoxes extends Component
{
    public float $receiptCost;
    public float $issueCost;
    public float $adjustmentCost;
    public float $balance;

    public function __construct()
    {
        $totals = $this->resolveTotals();

        $this->receiptCost    = (float) ($totals[RawMaterialDocumentType::RECEIPT->value]    ?? 0);
        $this->issueCost      = (float) ($totals[RawMaterialDocumentType::ISSUE->value]      ?? 0);
        $this->adjustmentCost = (float) ($totals[RawMaterialDocumentType::ADJUSTMENT->value] ?? 0);
        $this->balance        = $this->receiptCost - $this->issueCost + $this->adjustmentCost;
    }

    public function render(): View
    {
        return view('components.inventory.dashboard.document-cost-info-boxes');
    }

    /** @return array<string, float> */
    private function resolveTotals(): array
    {
        return RawMaterialDocument::where('status', RawMaterialDocumentStatus::ACCEPTED)
            ->where('effective_at', '>=', now()->subDays(30))
            ->selectRaw('type, SUM(total_cost) AS total')
            ->groupBy('type')
            ->pluck('total', 'type')
            ->toArray();
    }
}
