<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-chart-pie mr-1"></i>{{ $title }}
        </h3>
    </div>
    <div class="card-body">
        @if (empty($values))
            <p class="text-muted text-center mb-0">Sin datos disponibles.</p>
        @else
            <canvas id="{{ $chartId }}" height="220"></canvas>
        @endif
    </div>
</div>

@if (!empty($values))
    @push('js')
        <script type="module">
            const truncate = (str, max = 20) => str.length > max ? str.slice(0, max) + '…' : str;

            const numberFormatter = new Intl.NumberFormat('en-US', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            });

            new Chart(document.getElementById('{{ $chartId }}'), {
                type: 'pie',
                data: {
                    labels: @json($labels).map(l => truncate(l)),
                    datasets: [{
                        data: @json($values),
                        backgroundColor: [
                            '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b',
                            '#858796', '#5a5c69', '#2e59d9', '#17a673', '#2c9faf',
                        ],
                        borderWidth: 1,
                        borderColor: '#fff',
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                        },
                        tooltip: {
                            callbacks: {
                                label: (ctx) => {
                                    const value = ctx.parsed;
                                    const total = ctx.chart._metasets[ctx.datasetIndex].total;
                                    const percentage = Math.round((value / total) * 100);

                                    return ` ${ctx.label}: ${numberFormatter.format(value)} (${percentage}%)`;
                                },
                            },
                        },
                    },
                },
            });
        </script>
    @endpush
@endif
