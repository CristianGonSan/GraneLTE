<?php

namespace App\Exports\Excel;

use Illuminate\Support\Collection;
use App\Exports\Excel\BaseExport;
use App\Models\CattleRaising\Milking;

class ExportMilkings extends BaseExport
{
    /**
     * @param Collection<int, Milking> $results
     * @param array<int, string> $onlyColumns
     */
    public function __construct(Collection $results, array $onlyColumns = [])
    {
        parent::__construct($results, [
            'id' => [
                'header' => 'ID',
            ],
            'weight' => [
                'header' => 'Litros',
                'format' => fn(Milking $item): string => number_format($item->milk_quantity, 3)
            ],
            'cattle_id' => [
                'header' => 'Animal',
                'format' => fn(Milking $item): string => $item->cattle->identify()
            ],
            'milking_date' => [
                'header' => 'Ordeñado el',
                'format' => fn(Milking $item): string => $item->milking_date->format('d/m/Y')
            ],
            'notes' => [
                'header' => 'Notas',
                'format' => fn(Milking $item): string => $item->notes ?? 'Sin notas'
            ]
        ], $onlyColumns);
    }
}
