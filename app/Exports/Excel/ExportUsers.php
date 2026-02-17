<?php

namespace App\Exports\Excel;

use Illuminate\Support\Collection;
use App\Exports\Excel\BaseExport;
use App\Models\User;

class ExportUsers extends BaseExport
{
    /**
     * @param Collection<int, User> $results
     * @param array<int, string> $onlyColumns
     */
    public function __construct(Collection $results, array $onlyColumns = [])
    {
        parent::__construct($results, [
            'id' => [
                'header' => 'ID',
            ],
            'name' => [
                'header' => 'Nombre',
            ],
            'email' => [
                'header' => 'Email',
            ],
            'created_at' => [
                'header' => 'Creado el',
                'format' => fn(User $item): string => $item->created_at->format('d/m/Y')
            ],
            'enabled' => [
                'header' => 'Está',
                'format' => fn(User $item): string => $item->isEnabled() ? 'Habilitado' : 'Deshabilitado'
            ]
        ], $onlyColumns);
    }
}
