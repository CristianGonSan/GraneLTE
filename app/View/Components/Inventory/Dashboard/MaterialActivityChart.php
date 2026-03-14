<?php

namespace App\View\Components\Inventory\Dashboard;

use App\Models\Inventory\RawMaterialMovement;
use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Illuminate\View\View;

class MaterialActivityChart extends Component
{
    public array $labels = [];
    public array $values = [];

    public function __construct()
    {
        $this->resolveData();
    }

    public function render(): View
    {
        return view('components.inventory.dashboard.material-activity-chart');
    }

    private function resolveData(): void
    {
        $results = RawMaterialMovement::query()
            ->selectRaw('
                raw_materials.name  AS label,
                COUNT(*)            AS total
            ')
            ->join('raw_material_batches', 'raw_material_batches.id', '=', 'raw_material_movements.batch_id')
            ->join('raw_materials', 'raw_materials.id', '=', 'raw_material_batches.material_id')
            ->where('raw_material_movements.effective_at', '>=', now()->subDays(30))
            ->groupBy('raw_materials.id', 'raw_materials.name')
            ->orderByDesc('total')
            ->get();

        $top    = $results->take(9);
        $others = $results->slice(9);

        $this->labels = $top->pluck('label')->toArray();
        $this->values = $top->pluck('total')
            ->map(fn(mixed $v): int => (int) $v)
            ->toArray();

        if ($others->isNotEmpty()) {
            $this->labels[] = 'Otros';
            $this->values[] = $others->sum('total');
        }
    }
}
