<?php

namespace App\Exports\Excel;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BaseExport implements FromCollection, WithHeadings
{
    protected $collection;
    protected $headings;

    public function __construct(Collection $results, array $columnFormatter, array $onlyColumns = [])
    {
        if (empty($onlyColumns)) {
            $this->map($results, $columnFormatter);
        } else {
            $this->map($results, array_intersect_key($columnFormatter, array_flip($onlyColumns)));
        }
    }

    private function map(Collection $results, array $columns): void
    {
        $this->headings = array_column($columns, 'header');

        $this->collection = $results->map(function ($item) use ($columns): array {
            $row = [];

            foreach ($columns as $column => $map) {
                $format = $map['format'] ?? null;
                $row[$column] = $format ? $format($item) : $item->$column;
            }

            return $row;
        });
    }

    public function collection(): Collection
    {
        return $this->collection;
    }

    public function headings(): array
    {
        return $this->headings;
    }
}
