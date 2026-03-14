<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-chart-bar"></i><span class="ml-1">Materiales con mayor actividad</span>
        </h3>
        <div class="card-tools">
            <small class="text-muted">Últimos 30 días</small>
        </div>
    </div>
    <div class="card-body">
        @if (empty($values))
            <p class="text-muted text-center mb-0">Sin movimientos en los últimos 30 días.</p>
        @else
            <canvas id="chart-material-activity" height="220"></canvas>
        @endif
    </div>
</div>

@if (!empty($values))
    @push('js')
        <script type="module">
            const truncate = (str, max = 20) => str.length > max ? str.slice(0, max) + '…' : str;

            new Chart(document.getElementById('chart-material-activity'), {
                type: 'bar',
                data: {
                    labels: @json($labels).map(l => truncate(l)),
                    datasets: [{
                        data: @json($values),
                        backgroundColor: [
                            '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b',
                            '#858796', '#5a5c69', '#2e59d9', '#17a673', '#2c9faf',
                        ],
                        borderWidth: 0,
                    }],
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: (ctx) => ' ' + Math.round(ctx.parsed.x).toLocaleString('en') + ' movimientos',
                            },
                        },
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0,
                                callback: (v) => Math.round(v).toLocaleString('en'),
                            },
                        },
                    },
                },
            });
        </script>
    @endpush
@endif
