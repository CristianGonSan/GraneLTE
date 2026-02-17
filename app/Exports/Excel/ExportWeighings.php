<?php

namespace App\Exports\Excel;

use Illuminate\Support\Collection;
use App\Exports\Excel\BaseExport;
use App\Models\CattleRaising\Weighing;

class ExportWeighings extends BaseExport
{
    /**
     * @param Collection<int, Weighing> $results
     * @param array<int, string> $onlyColumns
     */
    public function __construct(Collection $results, array $onlyColumns = [])
    {
        parent::__construct($results, [
            'id' => [
                'header' => 'ID',
            ],
            'weight' => [
                'header' => 'Peso (kg)',
                //'format' => fn(Weighing $item): string => number_format($item->weight, 3)
            ],
            'cattle_id' => [
                'header' => 'Animal',
                'format' => fn(Weighing $item): string => $item->cattle->identify()
            ],
            'weighing_date' => [
                'header' => 'Pesado el',
                'format' => fn(Weighing $item): string => $item->weighing_date->format('d/m/Y')
            ],
            'weighing_type' => [
                'header' => 'Tipo',
                'format' => fn(Weighing $item): string => $item->weighing_type->label()
            ],
            'notes' => [
                'header' => 'Notas',
                'format' => fn(Weighing $item): string => $item->notes ?? 'Sin notas'
            ]
        ], $onlyColumns);
    }
}
