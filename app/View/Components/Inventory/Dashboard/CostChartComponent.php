<?php

namespace App\View\Components\Inventory\Dashboard;

use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Illuminate\View\View;

abstract class CostChartComponent extends Component
{
    public array $labels = [];
    public array $values = [];
    public string $title;
    public string $chartId;

    public function render(): View
    {
        return view('components.inventory.dashboard.cost-chart');
    }

    protected function applyTopNWithOthers(Collection $results, int $n = 9): void
    {
        $top    = $results->take($n);
        $others = $results->slice($n);

        $this->labels = $top->pluck('label')->toArray();
        $this->values = $top->pluck('total_cost')
            ->map(fn(mixed $v): float => (float) $v)
            ->toArray();

        if ($others->isNotEmpty()) {
            $this->labels[] = 'Otros';
            $this->values[] = round((float) $others->sum('total_cost'), 2);
        }
    }
}
