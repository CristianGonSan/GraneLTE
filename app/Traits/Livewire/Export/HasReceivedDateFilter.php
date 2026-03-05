<?php

namespace App\Traits\Livewire\Export;

use Illuminate\Database\Eloquent\Builder;

trait HasReceivedDateFilter
{
    public ?string $receivedFrom = null;
    public ?string $receivedTo   = null;

    protected function applyReceivedDateFilter(Builder $query, string $column): Builder
    {
        return $query
            ->when($this->receivedFrom, fn(Builder $q): Builder => $q->where($column, '>=', $this->receivedFrom))
            ->when($this->receivedTo,   fn(Builder $q): Builder => $q->where($column, '<=', $this->receivedTo));
    }
}
