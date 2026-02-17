@props([
    'modelSearch' => 'searchTerm',
    'modelPerPage' => 'perPage',
    'actionSearch' => 'search',
    'target' => '',
])

@php
    $hasFilters = !$slot->isEmpty();
@endphp

<div class="row">
    <div class="col-md-2 col-sm-4 mb-1">
        <select class="custom-select" wire:model="{{ $modelPerPage }}" wire:change="{{ $actionSearch }}">
            <option value="12">12 registros</option>
            <option value="24">24 registros</option>
            <option value="48">48 registros</option>
        </select>
    </div>

    <div class="col-md-{{ $hasFilters ? '9' : '10' }} col-sm-8 col-12 mb-1">
        <div class="input-group">
            <input id="searchInput" type="text" class="form-control" placeholder="Presiona Enter para buscar..."
                wire:model="{{ $modelSearch }}" wire:keydown.enter="{{ $actionSearch }}" autofocus>

            <div class="input-group-append">
                <x-livewire.loading-button icon="magnifying-glass" wire:click="{{ $actionSearch }}"
                    wire:target="{{ $target }}" />
            </div>
        </div>
    </div>

    @if ($hasFilters)
        <div class="col-md-1 col-12 mb-1">
            <button class="btn btn-outline-primary btn-block" type="button" data-toggle="collapse"
                data-target="#collapseFilters" aria-expanded="false" aria-controls="collapseFilters">
                <i class="fas fa-filter"></i>
            </button>
        </div>
    @endif
</div>

@if ($hasFilters)
    <div id="collapseFilters" class="collapse" wire:ignore.self>
        {{ $slot }}
    </div>
@endif
