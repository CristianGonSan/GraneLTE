<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-chart-line"></i><span class="ml-1">Tendencia de costos de movimientos
                (últimos 12 meses)</span>
        </h3>
    </div>
    <div class="card-body">
        @if (empty($datasets))
            <p class="text-muted text-center mb-0">Sin movimientos registrados en los últimos 12 meses.</p>
        @else
            <canvas id="chart-movement-trend" height="200"></canvas>
        @endif
    </div>
</div>

@if (!empty($datasets))
    @push('js')
        <script type="module">
            const months = @json($months);
            const raw = @json($datasets);

            const labels = months.map(m => {
                const [y, mo] = m.split('-');
                return new Date(y, mo - 1).toLocaleString('es', {
                    month: 'short',
                    year: '2-digit'
                });
            });

            const datasets = raw.map(ds => ({
                label: ds.label,
                data: ds.data,
                borderColor: ds.color,
                backgroundColor: ds.color + '33',
                borderWidth: 2,
                pointRadius: 3,
                fill: false,
                tension: 0.3,
            }));

            new Chart(document.getElementById('chart-movement-trend'), {
                type: 'line',
                data: {
                    labels,
                    datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        },
                        tooltip: {
                            callbacks: {
                                label: (ctx) => ' ' + ctx.dataset.label + ': $ ' +
                                    Math.round(ctx.parsed.y).toLocaleString('en'),
                            },
                        },
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: (v) => Math.round(v).toLocaleString('en'),
                            },
                        },
                    },
                },
            });
        </script>
    @endpush
@endif
