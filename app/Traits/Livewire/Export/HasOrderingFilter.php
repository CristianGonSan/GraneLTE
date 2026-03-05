<?php

namespace App\Traits\Livewire\Export;

use Illuminate\Database\Eloquent\Builder;

trait HasOrderingFilter
{
    protected function sanitizedDirection(): string
    {
        return $this->orderDirection === 'desc' ? 'desc' : 'asc';
    }

    /**
     * Implementación por defecto. Cada componente puede sobreescribirla
     * cuando necesite ordenamiento por columnas calculadas o CASE.
     */
    protected function applyOrdering(Builder $query): Builder
    {
        return $query->orderBy(
            $this->resolveOrderColumn($this->orderBy),
            $this->sanitizedDirection()
        );
    }

    abstract public function sortableColumns(): array;
    abstract protected function resolveOrderColumn(string $key): string;
}
