<x-admin.layout title="Dashboard do Bot">
    <div class="page-wrapper">
        <div class="content container-fluid" style="padding: 5%">
            
            <!-- Page Header -->
            <x-header.titulo pageTitle="Dashboard do Bot"/>
            <!-- /Page Header -->
            <x-alert/>

            <div class="row">
                <!-- Métrica 1 -->
                <div class="col-lg-2 col-md-4 col-sm-6 mb-4">
                    <div class="card shadow text-center">
                        <div class="card-header">
                            <h6 class="mb-0">Bots Ativos</h6>
                        </div>
                        <div class="card-body">
                            <h2>{{ $botsAtivos }}</h2>
                        </div>
                    </div>
                </div>

                <!-- Métrica 2 -->
                <div class="col-lg-2 col-md-4 col-sm-6 mb-4">
                    <div class="card shadow text-center">
                        <div class="card-header">
                            <h6 class="mb-0">Tokens Consumidos</h6>
                        </div>
                        <div class="card-body">
                            <h2>{{ number_format($totalTokens, 0, ',', '.') }}</h2>
                        </div>
                    </div>
                </div>

                <!-- Métrica 3 -->
                <div class="col-lg-2 col-md-4 col-sm-6 mb-4">
                    <div class="card shadow text-center">
                        <div class="card-header">
                            <h6 class="mb-0">Custo Estimado</h6>
                        </div>
                        <div class="card-body">
                            <h2>R$ {{ number_format($custoEstimado, 2, ',', '.') }}</h2>
                        </div>
                    </div>
                </div>

                <!-- Métrica 4 -->
                <div class="col-lg-2 col-md-4 col-sm-6 mb-4">
                    <div class="card shadow text-center">
                        <div class="card-header">
                            <h6 class="mb-0">Valor Cobrado</h6>
                        </div>
                        <div class="card-body">
                            <h2>R$ {{ number_format($totalCusto ?? 0, 2, ',', '.') }}</h2>
                        </div>
                    </div>
                </div>

                <!-- Métrica 5 -->
                <div class="col-lg-2 col-md-4 col-sm-6 mb-4">
                    <div class="card shadow text-center">
                        <div class="card-header">
                            <h6 class="mb-0">Conversas Hoje</h6>
                        </div>
                        <div class="card-body">
                            <h2>{{ $conversasHoje }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Serviços do Bot -->
            <div class="card shadow mb-5">
                <div class="card-header">
                    <h5 class="card-title mb-0">Serviços do Bot</h5>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Serviço</th>
                                <th>Professor</th>
                                <th>Horário</th>
                                <th>Valor</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($services as $service)
                            <tr>
                                <td>{{ $service->nome_servico }}</td>
                                <td>{{ $service->professor }}</td>
                                <td>{{ $service->horario }}</td>
                                <td>R$ {{ number_format($service->valor, 2, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Gráfico de Tokens -->
            <div class="card shadow mb-5">
                <div class="card-header">
                    <h5 class="card-title mb-0">Consumo de Tokens (Últimos 7 dias)</h5>
                </div>
                <div class="card-body">
                    <canvas id="tokensChart"></canvas>
                </div>
            </div>

        </div>
    </div>

    @section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('tokensChart');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($labels),
                datasets: [{
                    label: 'Tokens usados',
                    data: @json($dataTokens),
                    borderWidth: 2,
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.2)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Tokens: ' + context.parsed.y.toLocaleString('pt-BR');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
    @endsection
</x-admin.layout>
