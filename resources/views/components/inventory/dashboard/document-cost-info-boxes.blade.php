<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-file-invoice-dollar"></i><span class="ml-1">Costos por documentos</span>
        </h3>
        <div class="card-tools">
            <small class="text-muted">Últimos 30 días</small>
        </div>
    </div>
    <div class="card-body">
        <div class="row">

            {{-- Entradas --}}
            <div class="col-lg-3 col-md-6 col-sm-12 border-right text-center py-3">
                <div class="text-info mb-2">
                    <i class="fas fa-arrow-right-to-bracket fa-2x"></i>
                </div>
                <div class="text-muted text-sm mb-1">Entradas</div>
                <div class="h4 mb-0 font-weight-bold">
                    {{ number_format($receiptCost, 0) }}
                </div>
            </div>

            {{-- Salidas --}}
            <div class="col-lg-3 col-md-6 col-sm-12 border-right text-center py-3">
                <div class="text-danger mb-2">
                    <i class="fas fa-arrow-right-from-bracket fa-2x"></i>
                </div>
                <div class="text-muted text-sm mb-1">Salidas</div>
                <div class="h4 mb-0 font-weight-bold">
                    {{ number_format($issueCost, 0) }}
                </div>
            </div>

            {{-- Ajustes --}}
            <div class="col-lg-3 col-md-6 col-sm-12 border-right text-center py-3">
                <div class="text-warning mb-2">
                    <i class="fas fa-sliders-h fa-2x"></i>
                </div>
                <div class="text-muted text-sm mb-1">Ajustes</div>
                <div class="h4 mb-0 font-weight-bold">
                    {{ number_format($adjustmentCost, 0) }}
                </div>
            </div>

            {{-- Balance --}}
            <div class="col-lg-3 col-md-6 col-sm-12 text-center py-3">
                <div class="{{ $balance >= 0 ? 'text-success' : 'text-danger' }} mb-2">
                    <i class="fas {{ $balance >= 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }} fa-2x"></i>
                </div>
                <div class="text-muted text-sm mb-1">Balance</div>
                <div class="h4 mb-0 font-weight-bold {{ $balance >= 0 ? 'text-success' : 'text-danger' }}">
                    {{ ($balance >= 0 ? '+' : '') . number_format($balance, 0) }}
                </div>
            </div>

        </div>
    </div>
</div>
