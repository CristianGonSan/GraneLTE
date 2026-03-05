<div class="card">
    <div class="card-header d-flex align-items-center border-bottom">
        <h3 class="card-title mr-auto mb-0">
            <i class="fas fa-chart-bar mr-1 text-primary"></i>
            Movimientos — {{ $this->material->shortText('name') }}
        </h3>
        <div class="card-tools d-flex align-items-center gap-2">
            <i wire:loading class="fas fa-spinner fa-spin text-primary mr-2"></i>
            <select wire:model.live="days" class="form-control form-control-sm" style="width: auto">
                <option value="7">Últimos 7 días</option>
                <option value="30">Últimos 30 días</option>
                <option value="90">Últimos 90 días</option>
            </select>
        </div>
    </div>

    <div class="card-body p-3">
        {{-- Tarjetas de resumen --}}
        <div class="row mb-3 text-center">
            <div class="col-4">
                <div class="p-2 rounded" style="background:#e9fbe9">
                    <div class="text-success font-weight-bold" style="font-size:1.1rem">
                        <i class="fas fa-arrow-up mr-1"></i>
                        {{ number_format($chartData['summary']['total_in'], 3) }}
                    </div>
                    <small class="text-muted">Entradas</small>
                </div>
            </div>
            <div class="col-4">
                <div class="p-2 rounded" style="background:#fdecea">
                    <div class="text-danger font-weight-bold" style="font-size:1.1rem">
                        <i class="fas fa-arrow-down mr-1"></i>
                        {{ number_format($chartData['summary']['total_out'], 3) }}
                    </div>
                    <small class="text-muted">Salidas</small>
                </div>
            </div>
            <div class="col-4">
                <div class="p-2 rounded" style="background:#e8f4fd">
                    <div class="text-info font-weight-bold" style="font-size:1.1rem">
                        <i class="fas fa-balance-scale mr-1"></i>
                        {{ number_format($chartData['summary']['net'], 3) }}
                    </div>
                    <small class="text-muted">Neto</small>
                </div>
            </div>
        </div>

        {{-- Gráfica --}}
        <div wire:ignore>
            <canvas id="movements-chart-{{ $materialId }}" style="min-height:300px"></canvas>
        </div>
    </div>
</div>

@script
    <script>
        const TYPE_CONFIG = {
            receipt: {
                label: 'Entradas (recepción)',
                color: 'rgba( 40,167, 69,.75)',
                border: '#28a745'
            },
            issue: {
                label: 'Salidas (despacho)',
                color: 'rgba(220, 53, 69,.75)',
                border: '#dc3545'
            },
            transfer_in: {
                label: 'Transferencia entrada',
                color: 'rgba( 23,162,184,.75)',
                border: '#17a2b8'
            },
            transfer_out: {
                label: 'Transferencia salida',
                color: 'rgba(255,193,  7,.75)',
                border: '#ffc107'
            },
            adjustment_pos: {
                label: 'Ajuste positivo',
                color: 'rgba(111, 66,193,.75)',
                border: '#6f42c1'
            },
            adjustment_neg: {
                label: 'Ajuste negativo',
                color: 'rgba(253,126, 20,.75)',
                border: '#fd7e14'
            },
        };

        const buildDatasets = (labels, grouped) =>
            Object.entries(grouped).map(([type, values]) => {
                const cfg = TYPE_CONFIG[type] ?? {
                    label: type,
                    color: 'rgba(108,117,125,.75)',
                    border: '#6c757d'
                };
                return {
                    label: cfg.label,
                    data: labels.map(d => values[d] ?? 0),
                    backgroundColor: cfg.color,
                    borderColor: cfg.border,
                    borderWidth: 1.5,
                    borderRadius: 6,
                    borderSkipped: false,
                    hoverBackgroundColor: cfg.border,
                };
            });

        const ctx = document.getElementById('movements-chart-{{ $materialId }}').getContext('2d');

        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($chartData['labels']),
                datasets: buildDatasets(@json($chartData['labels']), @json($chartData['grouped'])),
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 400,
                    easing: 'easeOutQuart'
                },
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'rectRounded',
                            padding: 16,
                            font: {
                                size: 12
                            },
                        },
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,.75)',
                        padding: 10,
                        cornerRadius: 6,
                        callbacks: {
                            label: ctx => {
                                const v = ctx.parsed.y;
                                if (v === 0) return null; // ocultar series vacías
                                return ` ${ctx.dataset.label}: ${v.toLocaleString('es', { minimumFractionDigits: 3 })}`;
                            },
                            // Mostrar total en el footer del tooltip
                            footer: items => {
                                const total = items.reduce((s, i) => s + i.parsed.y, 0);
                                return total ?
                                    `Total: ${total.toLocaleString('es', { minimumFractionDigits: 3 })}` : '';
                            },
                        },
                    },
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            maxRotation: 45,
                            font: {
                                size: 11
                            }
                        },
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0,0,0,.06)'
                        },
                        ticks: {
                            font: {
                                size: 11
                            },
                            callback: v => v.toLocaleString('es'),
                        },
                    },
                },
            },
        });

        $wire.on('chartDataUpdated', ({
            labels,
            grouped
        }) => {
            chart.data.labels = labels;
            chart.data.datasets = buildDatasets(labels, grouped);
            chart.update('active');
        });
    </script>
@endscript
