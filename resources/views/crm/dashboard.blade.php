<x-admin.layout title="CRM - Dashboard">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col-sm-8">
                        <h3 class="page-title">CRM</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('cliente.dashboard') }}">Admin</a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ul>
                    </div>
                    <div class="col-sm-4 text-end">
                        <a href="{{ route('crm.leads.create') }}" class="btn btn-primary">Novo Lead</a>
                    </div>
                </div>
            </div>

            @include('crm._nav')
            <x-alert-messages />

            <div class="row">
                @foreach([
                    ['Novos leads 30d', $novosLeads30d, 'primary'],
                    ['Conversao lead/aluno', $taxaConversao . '%', 'success'],
                    ['Aulas na semana', $aulasSemana, 'info'],
                    ['Custo por lead', 'R$ ' . number_format($cpl, 2, ',', '.'), 'warning'],
                ] as $card)
                    <div class="col-xl-3 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <p class="text-muted mb-1">{{ $card[0] }}</p>
                                <h3 class="mb-0 text-{{ $card[2] }}">{{ $card[1] }}</h3>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="row">
                <div class="col-lg-7">
                    <div class="card">
                        <div class="card-header"><h5 class="card-title mb-0">Funil de conversao</h5></div>
                        <div class="card-body">
                            @foreach($funil as $etapa)
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between">
                                        <strong>{{ $etapa['label'] }}</strong>
                                        <span>{{ $etapa['count'] }} leads · {{ $etapa['percent'] }}%</span>
                                    </div>
                                    <div class="progress mt-1">
                                        <div class="progress-bar" style="width: {{ $etapa['percent'] }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="card">
                        <div class="card-header"><h5 class="card-title mb-0">Origem dos leads</h5></div>
                        <div class="card-body"><canvas id="origensChart" height="240"></canvas></div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-7">
                    <div class="card">
                        <div class="card-header"><h5 class="card-title mb-0">Campanhas ativas</h5></div>
                        <div class="card-body table-responsive">
                            <table class="table table-hover">
                                <thead><tr><th>Campanha</th><th>Canal</th><th>Leads</th><th>CPL</th><th>Tendencia</th></tr></thead>
                                <tbody>
                                    @forelse($campanhas as $campanha)
                                        <tr>
                                            <td>{{ $campanha->nome }}</td>
                                            <td>{{ $campanha->canal }}</td>
                                            <td>{{ $campanha->leads_count }}</td>
                                            <td>R$ {{ number_format($campanha->cpl, 2, ',', '.') }}</td>
                                            <td><span class="text-success">up</span></td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="text-center">Nenhuma campanha ativa.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="card">
                        <div class="card-header"><h5 class="card-title mb-0">Tarefas de hoje</h5></div>
                        <div class="card-body">
                            @forelse($tarefasHoje as $tarefa)
                                <form method="POST" action="{{ route('crm.tarefas.concluir', $tarefa) }}" class="d-flex justify-content-between border-bottom py-2">
                                    @csrf @method('PATCH')
                                    <div>
                                        <strong>{{ $tarefa->tipo }}</strong>
                                        <div class="text-muted small">{{ $tarefa->descricao }}</div>
                                        @if($tarefa->lead)<a href="{{ route('crm.leads.show', $tarefa->lead) }}">{{ $tarefa->lead->nome }}</a>@endif
                                    </div>
                                    <button class="btn btn-sm btn-outline-success">Concluir</button>
                                </form>
                            @empty
                                <p class="text-muted mb-0">Sem tarefas pendentes para hoje.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        new Chart(document.getElementById('origensChart'), {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($origens->keys()->values()) !!},
                datasets: [{ data: {!! json_encode($origens->values()->values()) !!}, backgroundColor: ['#0d6efd','#20c997','#ffc107','#dc3545','#6f42c1','#6c757d'] }]
            }
        });
    </script>
</x-admin.layout>
