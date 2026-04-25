<x-admin.layout title="CRM - Relatorios">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header"><h3 class="page-title">Relatorios CRM</h3></div>
            @include('crm._nav')

            <div class="row">
                <div class="col-md-6">
                    <div class="card"><div class="card-body"><p class="text-muted mb-1">Churn mensal</p><h3>{{ $churnMensal }}%</h3></div></div>
                </div>
                <div class="col-md-6">
                    <div class="card"><div class="card-body"><p class="text-muted mb-1">LTV medio por plano</p><h3>R$ {{ number_format($ltvMedio, 2, ',', '.') }}</h3></div></div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header"><h5 class="card-title mb-0">Conversao por origem</h5></div>
                        <div class="card-body table-responsive">
                            <table class="table">
                                <thead><tr><th>Origem</th><th>Leads</th><th>Convertidos</th><th>Taxa</th></tr></thead>
                                <tbody>
                                    @foreach($conversaoPorOrigem as $linha)
                                        <tr><td>{{ $linha['origem'] }}</td><td>{{ $linha['total'] }}</td><td>{{ $linha['convertidos'] }}</td><td>{{ $linha['taxa'] }}%</td></tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header"><h5 class="card-title mb-0">Evolucao de matriculas</h5></div>
                        <div class="card-body"><canvas id="matriculasChart" height="220"></canvas></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        new Chart(document.getElementById('matriculasChart'), {
            type: 'line',
            data: {
                labels: {!! json_encode($evolucaoMatriculas->pluck('mes')) !!},
                datasets: [{ label: 'Matriculas', data: {!! json_encode($evolucaoMatriculas->pluck('total')) !!}, borderColor: '#0d6efd', backgroundColor: 'rgba(13,110,253,.12)', fill: true, tension: .35 }]
            }
        });
    </script>
</x-admin.layout>
