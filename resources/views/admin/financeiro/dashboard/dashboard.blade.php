<x-admin.layout title="Dashboard Financeiro">
    <!-- CSS Customizado -->
   @include('admin.financeiro.dashboard.style')

   <!-- Modal de Detalhes Financeiros -->
<div class="modal fade" id="detalhesModal" tabindex="-1" aria-labelledby="detalhesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header">
                <h5 class="modal-title" id="detalhesModalLabel">Detalhes Financeiros</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <table class="table table-dark table-striped" id="tabelaDetalhes">
                    <thead>
                        <tr>
                            <th>Descrição</th>
                            <th>Valor (R$)</th>
                            <th>Status</th>
                            <th>Data Vencimento</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Dados serão inseridos via JS -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button id="exportExcel" class="btn btn-success">Exportar para Excel</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>


        <div class="page-wrapper">
            <div class="content container-fluid">
                <div class="dashboard-title">
                    <span>💼</span> Dashboard Financeiro
                </div>

                <!-- Filtro -->
           @include('admin.financeiro.dashboard.filtro')



            <!-- Métricas Principais -->
            @include('admin.financeiro.dashboard.visaogeral')

            <!-- Receitas Detalhadas -->
            @include('admin.financeiro.dashboard.receitas')


            <!-- Gráficos -->
            <div class="row">
                <div class="col-lg-8">
                    <div class="chart-container">
                        <h5 class="chart-title">
                            <span>📊</span> Fluxo Financeiro do Período
                        </h5>
                        <canvas id="fluxoChart"></canvas>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="chart-container">
                        <h5 class="chart-title">
                            <span>🥧</span> Distribuição de Receitas
                        </h5>
                        <canvas id="pieChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Configuração do gráfico de fluxo
        const ctx = document.getElementById('fluxoChart').getContext('2d');
        const fluxoChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Receitas do Período', 'Despesas do Período', 'Saldo'],
                datasets: [{
                    label: 'Valores (R$)',
                    data: [{{ $receitasMes }}, {{ $despesasMes }}, {{ $receitasMes - $despesasMes }}],
                    backgroundColor: [
                        'rgba(0, 212, 170, 0.8)',
                        'rgba(255, 65, 108, 0.8)',
                        '{{ ($receitasMes - $despesasMes) >= 0 ? "rgba(0, 212, 170, 0.6)" : "rgba(255, 65, 108, 0.6)" }}'
                    ],
                    borderColor: [
                        'rgba(0, 212, 170, 1)',
                        'rgba(255, 65, 108, 1)',
                        '{{ ($receitasMes - $despesasMes) >= 0 ? "rgba(0, 212, 170, 1)" : "rgba(255, 65, 108, 1)" }}'
                    ],
                    borderWidth: 2,
                    borderRadius: 12,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        labels: {
                            color: 'white',
                            font: {
                                size: 12,
                                weight: '500'
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: 'white',
                        bodyColor: 'white',
                        borderColor: 'rgba(255, 255, 255, 0.2)',
                        borderWidth: 1,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                return 'R$ ' + context.parsed.y.toLocaleString('pt-BR', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                });
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: 'white',
                            font: {
                                size: 11
                            },
                            callback: function(value) {
                                return 'R$ ' + value.toLocaleString('pt-BR');
                            }
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)',
                            drawBorder: false
                        }
                    },
                    x: {
                        ticks: {
                            color: 'white',
                            font: {
                                size: 11,
                                weight: '500'
                            }
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Configuração do gráfico de pizza
        const ctxPie = document.getElementById('pieChart').getContext('2d');
        const pieChart = new Chart(ctxPie, {
            type: 'doughnut',
            data: {
                labels: ['Recebidas', 'Pendentes'],
                datasets: [{
                    data: [{{ $totalReceitasRecebidas }}, {{ $totalReceitasPendentes }}],
                    backgroundColor: [
                        'rgba(0, 212, 170, 0.8)',
                        'rgba(251, 191, 36, 0.8)'
                    ],
                    borderColor: [
                        'rgba(0, 212, 170, 1)',
                        'rgba(251, 191, 36, 1)'
                    ],
                    borderWidth: 3,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                cutout: '65%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: 'white',
                            font: {
                                size: 12,
                                weight: '500'
                            },
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: 'white',
                        bodyColor: 'white',
                        borderColor: 'rgba(255, 255, 255, 0.2)',
                        borderWidth: 1,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((context.parsed / total) * 100).toFixed(1);
                                return context.label + ': R$ ' + context.parsed.toLocaleString('pt-BR', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                }) + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });

        // Animação suave no carregamento
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.metric-card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    card.style.transition = 'all 0.6s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });


        // Exemplo para o gráfico de pizza
pieChart.options.onClick = function(evt, elements) {
    if (elements.length > 0) {
        // Pega o índice do slice clicado
        const index = elements[0].index;
        const label = this.data.labels[index];

        // Filtrar dados do front-end (ou pegar via API depois)
        let dados = [];
        if (label === 'Recebidas') {
            dados = [
                @foreach($receitasDetalhadas as $r)
                    @if($r->status === 'RECEBIDA')
                        { descricao: "{{ $r->descricao }}", valor: "{{ $r->valor }}", status: "{{ $r->status }}", data_vencimento: "{{ $r->data_vencimento }}" },
                    @endif
                @endforeach
            ];
        } else {
            dados = [
                @foreach($receitasDetalhadas as $r)
                    @if($r->status === 'PENDENTE')
                        { descricao: "{{ $r->descricao }}", valor: "{{ $r->valor }}", status: "{{ $r->status }}", data_vencimento: "{{ $r->data_vencimento }}" },
                    @endif
                @endforeach
            ];
        }

        // Preencher tabela
        const tbody = document.querySelector('#tabelaDetalhes tbody');
        tbody.innerHTML = '';
        dados.forEach(d => {
            tbody.innerHTML += `<tr>
                <td>${d.descricao}</td>
                <td>${d.valor}</td>
                <td>${d.status}</td>
                <td>${d.data_vencimento}</td>
            </tr>`;
        });

        // Abrir modal
        const modal = new bootstrap.Modal(document.getElementById('detalhesModal'));
        modal.show();
    }
};





document.querySelectorAll('.card-indicador').forEach(card => {
    card.addEventListener('click', function() {
        const tipo = this.dataset.tipo;
        let dados = [];

        if (tipo === 'despesas') {
            dados = [
                @foreach($despesasDetalhadas as $d)
                    { descricao: "{{ $d->descricao }}", valor: "{{ $d->valor }}", status: "{{ $d->status }}", data_vencimento: "{{ $d->data_vencimento }}" },
                @endforeach
            ];
        } else if (tipo === 'receitas') {
            dados = [
                @foreach($receitasDetalhadas as $r)
                    { descricao: "{{ $r->descricao }}", valor: "{{ $r->valor }}", status: "{{ $r->status }}", data_vencimento: "{{ $r->data_vencimento }}" },
                @endforeach
            ];
        }

        // Preenche tabela
        const tbody = document.querySelector('#tabelaDetalhes tbody');
        tbody.innerHTML = '';
        dados.forEach(d => {
            tbody.innerHTML += `<tr>
                <td>${d.descricao}</td>
                <td>${d.valor}</td>
                <td>${d.status}</td>
                <td>${d.data_vencimento}</td>
            </tr>`;
        });

        // Mostra modal
        const modal = new bootstrap.Modal(document.getElementById('detalhesModal'));
        modal.show();
    });
});

    </script>
</x-admin.layout>