<?php

namespace App\Traits\Livewire\Export;

use Illuminate\Database\Eloquent\Builder;

trait HasExpirationFilter
{
    public ?string $expirationFilter = null;

    protected function applyExpirationFilter(Builder $query, string $column): Builder
    {
        return $query->when(
            (bool) $this->expirationFilter,
            function (Builder $q) use ($column): Builder {
                return match ($this->expirationFilter) {
                    'expired'       => $q->whereNotNull($column)->where($column, '<=', now()),
                    'expiring'      => $q->whereNotNull($column)->whereBetween($column, [now(), now()->addDays(30)]),
                    'no_expiration' => $q->whereNull($column),
                    default         => $q,
                };
            }
        );
    }
}
