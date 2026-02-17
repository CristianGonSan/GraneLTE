@props([
    'modelShow' => 'show',
    'size'      => 'md',
])

<div x-data="{ open: @entangle($modelShow) }" x-show="open" x-cloak>

    <!-- Fondo oscurecido -->
    <div class="modal-backdrop show" style="z-index: 1040;"></div>

    <!-- Contenedor del modal -->
    <div class="modal show d-block" role="dialog" aria-modal="true" style="z-index: 1050;">
        <div class="modal-dialog modal-dialog-centered modal-{{ $size }}" role="document">
            <div class="modal-content">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
