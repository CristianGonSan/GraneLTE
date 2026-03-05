<?php

namespace App\Traits\Livewire\Export;

use Illuminate\Database\Eloquent\Builder;

trait HasQuantityFilter
{
    public string $quantityOperator = '>';
    public int    $quantityValue    = 0;

    protected function applyQuantityFilter(Builder $query, string $column): Builder
    {
        return $query->when(
            \in_array($this->quantityOperator, ['=', '!=', '>', '<', '>=', '<='], true),
            fn(Builder $q): Builder => $q->where($column, $this->quantityOperator, $this->quantityValue)
        );
    }
}
