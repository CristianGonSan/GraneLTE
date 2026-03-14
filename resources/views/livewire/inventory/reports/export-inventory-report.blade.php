<form wire:submit.prevent="export">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-file-excel mr-1"></i>
                Reporte General de Inventario
            </h3>
        </div>

        <div class="card-body">

            {{-- Fila 1: Rango de movimientos --}}
            <div class="row">
                <x-adminlte-input name="movementsFrom" label="Movimientos desde" type="datetime-local"
                    fgroup-class="col-md-3" wire:model="movementsFrom" />

                <x-adminlte-input name="movementsTo" label="Movimientos hasta" type="datetime-local"
                    fgroup-class="col-md-3" wire:model="movementsTo" />

                <x-adminlte-input name="expiringDays" label="Dias para vencimiento" type="number"
                    fgroup-class="col-md-2" wire:model="expiringDays" min="1" max="365" />
            </div>

            {{-- Descripcion de hojas --}}
            <div class="row mt-1">
                <div class="col-12">
                    <p class="text-muted mb-1">El reporte incluye las siguientes hojas:</p>
                    <ul class="text-muted small mb-0">
                        <li><strong>Resumen</strong> — Metricas generales, totales por categoria y por almacen.</li>
                        <li><strong>Stock Bajo</strong> — Materiales cuya existencia actual es menor al minimo definido.
                        </li>
                        <li><strong>Vencimientos</strong> — Lotes vencidos o proximos a vencer segun los dias
                            configurados.</li>
                        <li><strong>Existencias por Almacen</strong> — Todas las existencias con cantidad disponible
                            mayor a cero.</li>
                        <li><strong>Movimientos Recientes</strong> — Movimientos dentro del rango de fechas
                            seleccionado.</li>
                        <li><strong>Anomalias</strong> — Stocks negativos y lotes caducados con stock.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-3">
        <x-livewire.loading-button type="submit" label="Generar Excel" icon="file-excel" theme="outline-success" />
    </div>
</form>
