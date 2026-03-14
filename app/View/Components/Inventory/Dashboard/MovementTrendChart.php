<?php

namespace App\View\Components\Inventory\Dashboard;

use App\Enums\Inventory\RawMaterialMovement\RawMaterialMovementType;
use App\Models\Inventory\RawMaterialMovement;
use Illuminate\View\Component;
use Illuminate\View\View;

class MovementTrendChart extends Component
{
    /** @var array<int, string> */
    public array $months = [];

    /** @var array<int, array{label: string, color: string, data: array<int, float>}> */
    public array $datasets = [];

    private const TYPES = [
        RawMaterialMovementType::RECEIPT,
        RawMaterialMovementType::ISSUE,
        RawMaterialMovementType::ADJUSTMENT_IN,
        RawMaterialMovementType::ADJUSTMENT_OUT,
    ];

    private const COLORS = [
        'receipt'        => '#4e73df',
        'issue'          => '#e74a3b',
        'adjustment_in'  => '#1cc88a',
        'adjustment_out' => '#f6c23e',
    ];

    public function __construct()
    {
        $this->months   = $this->buildMonths();
        $this->datasets = $this->buildDatasets();
    }

    public function render(): View
    {
        return view('components.inventory.dashboard.movement-trend-chart');
    }

    /** @return array<int, string> */
    private function buildMonths(): array
    {
        $months = [];

        for ($i = 11; $i >= 0; $i--) {
            $months[] = now()->startOfMonth()->subMonths($i)->format('Y-m');
        }

        return $months;
    }

    /** @return array<int, array{label: string, color: string, data: array<int, float>}> */
    private function buildDatasets(): array
    {
        $typeValues = array_map(fn(RawMaterialMovementType $t): string => $t->value, self::TYPES);

        $rows = RawMaterialMovement::query()
            ->selectRaw('type, DATE_FORMAT(effective_at, \'%Y-%m\') AS month, SUM(quantity) AS total')
            ->whereIn('type', $typeValues)
            ->where('effective_at', '>=', now()->startOfMonth()->subMonths(11))
            ->groupBy('type', 'month')
            ->get()
            ->groupBy('type');

        $datasets = [];

        foreach (self::TYPES as $type) {
            $rowsByMonth = ($rows->get($type->value) ?? collect())
                ->pluck('total', 'month')
                ->map(fn(mixed $v): float => (float) $v);

            $data = array_map(
                fn(string $m): float => $rowsByMonth->get($m, 0.0),
                $this->months
            );

            if (array_sum($data) === 0.0) {
                continue;
            }

            $datasets[] = [
                'label' => $type->label(),
                'color' => self::COLORS[$type->value],
                'data'  => $data,
            ];
        }

        return $datasets;
    }
}
