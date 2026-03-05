<?php

namespace App\Livewire\Charts;

use App\Enums\Inventory\RawMaterialMovement\RawMaterialMovementType;
use App\Models\Inventory\RawMaterial;
use App\Models\Inventory\RawMaterialMovement;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;

class RawMaterialMovementsChart extends Component
{
    public int $materialId;
    public int $days = 30;

    public function mount(int $materialId, int $days = 30): void
    {
        $this->materialId = $materialId;
        $this->days = \in_array($days, [7, 30, 90]) ? $days : 30;
    }

    public function render(): View
    {
        return view('livewire.charts.raw-material-movements-chart', [
            'chartData' => $this->chartData(),
        ]);
    }

    public function updatedDays(): void
    {
        if (!\in_array($this->days, [7, 30, 90])) {
            $this->days = 30;
        }

        $this->dispatch('chartDataUpdated', ...$this->chartData());
    }

    #[Computed]
    public function material(): RawMaterial
    {
        return RawMaterial::findOrFail($this->materialId, ['id', 'name']);
    }

    public function chartData(): array
    {
        $from = now()->subDays($this->days)->startOfDay();
        $to   = now()->endOfDay();

        $rows = RawMaterialMovement::query()
            ->join('raw_material_batches as b', 'b.id', '=', 'raw_material_movements.batch_id')
            ->where('b.material_id', $this->materialId)
            ->whereBetween('raw_material_movements.effective_at', [$from, $to])
            ->select([
                DB::raw("DATE_FORMAT(raw_material_movements.effective_at, '%Y-%m-%d') as period"),
                'raw_material_movements.type',
                DB::raw('SUM(raw_material_movements.quantity) as total'),
            ])
            ->groupBy('period', 'raw_material_movements.type')
            ->orderBy('period')
            ->get();

        $labels = [];
        $cursor = $from->copy();
        while ($cursor->lte($to)) {
            $labels[] = $cursor->format('Y-m-d');
            $cursor->addDay();
        }

        $grouped = [];
        foreach ($rows as $row) {
            $key = $this->typeKey($row->type);
            $grouped[$key][$row->period] = (float) $row->total;
        }

        $inKeys  = ['receipt', 'transfer_in', 'adjustment_pos'];
        $outKeys = ['issue', 'transfer_out', 'adjustment_neg'];
        $totalIn = $totalOut = 0;

        foreach ($grouped as $key => $values) {
            $sum = array_sum($values);
            if (\in_array($key, $inKeys))  $totalIn  += $sum;
            if (\in_array($key, $outKeys)) $totalOut += abs($sum);
        }

        return [
            'labels'  => $labels,
            'grouped' => $grouped,
            'summary' => [
                'total_in'  => round($totalIn, 3),
                'total_out' => round($totalOut, 3),
                'net'       => round($totalIn - $totalOut, 3),
            ],
        ];
    }

    private function typeKey(RawMaterialMovementType $type): string
    {
        return match ($type) {
            RawMaterialMovementType::RECEIPT       => 'receipt',
            RawMaterialMovementType::ISSUE         => 'issue',
            RawMaterialMovementType::TRANSFER_IN   => 'transfer_in',
            RawMaterialMovementType::TRANSFER_OUT  => 'transfer_out',
            RawMaterialMovementType::ADJUSTMENT_IN => 'adjustment_pos',
            RawMaterialMovementType::ADJUSTMENT_OUT => 'adjustment_neg',
            default                                => $type->value,
        };
    }
}
