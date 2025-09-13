<x-admin.layout title="Dashboard Financeiro">
    <!-- CSS Customizado -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-gradient: linear-gradient(135deg, #00d4aa 0%, #38ef7d 100%);
            --danger-gradient: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%);
            --warning-gradient: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
            --info-gradient: linear-gradient(135deg, #89f7fe 0%, #66a6ff 100%);
            --glass-bg: rgba(255, 255, 255, 0.1);
            --glass-border: rgba(255, 255, 255, 0.15);
            --shadow-light: 0 8px 32px rgba(31, 38, 135, 0.15);
            --shadow-hover: 0 15px 45px rgba(31, 38, 135, 0.25);
        }

        .page-wrapper {
            background: var(--primary-gradient);
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            position: relative;
            overflow-x: hidden;
        }

        .page-wrapper::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.05"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.05"/><circle cx="50" cy="50" r="1" fill="white" opacity="0.03"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            pointer-events: none;
        }

        .content {
            padding: 2rem 3% !important;
            position: relative;
            z-index: 1;
        }

        .dashboard-title {
            text-align: center;
            color: white;
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 3rem;
            text-shadow: 0 4px 20px rgba(0,0,0,0.4);
            background: linear-gradient(45deg, #fff, #e0e7ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .filter-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 2rem;
            margin-bottom: 3rem;
            box-shadow: var(--shadow-light);
        }

        .filter-form {
            display: flex;
            flex-wrap: wrap;
            align-items: end;
            gap: 1.5rem;
        }

        .form-group {
            flex: 1;
            min-width: 150px;
        }

        .form-group label {
            display: block;
            color: white;
            font-weight: 500;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            color: white;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.25);
            border-color: rgba(255, 255, 255, 0.4);
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.1);
            color: white;
        }

        .form-control option {
            background: #2d3748;
            color: white;
        }

        .btn-filter {
            background: linear-gradient(135deg, #4299e1, #3182ce);
            border: none;
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(66, 153, 225, 0.3);
        }

        .btn-filter:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(66, 153, 225, 0.4);
        }

        .metrics-section {
            margin-bottom: 3rem;
        }

        .section-title {
            color: white;
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .metric-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-light);
        }

        .metric-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: var(--success-gradient);
            transition: all 0.3s ease;
        }

        .metric-card.success::before { background: var(--success-gradient); }
        .metric-card.danger::before { background: var(--danger-gradient); }
        .metric-card.warning::before { 
            background: linear-gradient(135deg, #fbbf24, #f59e0b); 
            color: #92400e;
        }
        .metric-card.info::before { background: var(--info-gradient); }

        .metric-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: var(--shadow-hover);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .card-title {
            color: white !important;
            font-size: 1rem;
            font-weight: 500;
            margin: 0;
            opacity: 0.85;
            line-height: 1.4;
        }

        .card-icon {
            font-size: 2.5rem;
            opacity: 0.15;
            filter: blur(0.5px);
        }

        .card-value {
            color: white !important;
            font-size: 2.2rem;
            font-weight: 700;
            margin: 0.5rem 0;
            text-shadow: 0 2px 10px rgba(0,0,0,0.3);
            line-height: 1.2;
        }

        .card-subtitle {
            color: rgba(255,255,255,0.6);
            font-size: 0.85rem;
            font-weight: 400;
            margin: 0;
        }

        .chart-container {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 2.5rem;
            margin-top: 2rem;
            box-shadow: var(--shadow-light);
            transition: all 0.3s ease;
        }

        .chart-container:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-hover);
        }

        .chart-title {
            color: white;
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 2rem;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .content {
                padding: 2rem 2% !important;
            }
        }

        @media (max-width: 768px) {
            .dashboard-title {
                font-size: 2.2rem;
            }
            
            .filter-form {
                flex-direction: column;
                align-items: stretch;
            }
            
            .card-value {
                font-size: 1.8rem;
            }
            
            .metric-card {
                padding: 1.5rem;
            }
            
            .chart-container {
                padding: 1.5rem;
            }
        }

        @media (max-width: 576px) {
            .dashboard-title {
                font-size: 1.8rem;
            }
            
            .card-value {
                font-size: 1.5rem;
            }
        }

        /* Animações */
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .metric-card {
            animation: slideInUp 0.6s ease-out;
        }

        .metric-card:nth-child(1) { animation-delay: 0.1s; }
        .metric-card:nth-child(2) { animation-delay: 0.2s; }
        .metric-card:nth-child(3) { animation-delay: 0.3s; }
        .metric-card:nth-child(4) { animation-delay: 0.4s; }
    </style>

        <div class="page-wrapper">
            <div class="content container-fluid">
                <div class="dashboard-title">
                    <span>💼</span> Dashboard Financeiro
                </div>

                <!-- Filtro -->
            <div class="filter-card">
        <div class="section-title">
            <span>🔍</span> Filtrar Período
        </div>
        <form method="GET" action="{{ route('admin.financeiro.dashboard') }}" class="filter-form">
            <div class="form-group">
                <label for="data_inicio">De</label>
                <input type="date" 
                    name="data_inicio" 
                    id="data_inicio" 
                    class="form-control"
                    value="{{ request('data_inicio', now()->startOfMonth()->format('Y-m-d')) }}">
            </div>

            <div class="form-group">
                <label for="data_fim">Até</label>
                <input type="date" 
                    name="data_fim" 
                    id="data_fim" 
                    class="form-control"
                    value="{{ request('data_fim', now()->endOfMonth()->format('Y-m-d')) }}">
            </div>

            <button type="submit" class="btn-filter">
                <span>📊</span> Filtrar
            </button>
        </form>
    </div>


            <!-- Métricas Principais -->
            <div class="metrics-section">
                <div class="section-title">
                    <span>📈</span> Visão Geral
                </div>
                <div class="row">
                    <!-- Total de Receitas -->
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="metric-card success">
                            <div class="card-header">
                                <div>
                                    <h5 class="card-title">Receitas Totais</h5>
                                    <p class="card-value">R$ {{ number_format($totalReceitas, 2, ',', '.') }}</p>
                                </div>
                                <div class="card-icon">💰</div>
                            </div>
                        </div>
                    </div>

                    <!-- Total de Despesas -->
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="metric-card danger">
                            <div class="card-header">
                                <div>
                                    <h5 class="card-title">Despesas Totais</h5>
                                    <p class="card-value">R$ {{ number_format($totalDespesas, 2, ',', '.') }}</p>
                                </div>
                                <div class="card-icon">💸</div>
                            </div>
                        </div>
                    </div>

                    <!-- Saldo Atual -->
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="metric-card {{ ($totalReceitas - $totalDespesas) >= 0 ? 'success' : 'danger' }}">
                            <div class="card-header">
                                <div>
                                    <h5 class="card-title">Saldo Atual</h5>
                                    <p class="card-value">R$ {{ number_format($totalReceitas - $totalDespesas, 2, ',', '.') }}</p>
                                </div>
                                <div class="card-icon">💳</div>
                            </div>
                        </div>
                    </div>

                    <!-- Resultado do Mês -->
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="metric-card {{ ($receitasMes - $despesasMes) >= 0 ? 'success' : 'danger' }}">
                            <div class="card-header">
                                <div>
                                    <h5 class="card-title">Resultado do Mês</h5>
                                    <p class="card-value">R$ {{ number_format($receitasMes - $despesasMes, 2, ',', '.') }}</p>
                                </div>
                                <div class="card-icon">📊</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Receitas Detalhadas -->
            <div class="metrics-section">
                <div class="section-title">
                    <span>💵</span> Receitas
                </div>
                <div class="row">
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="metric-card info">
                            <div class="card-header">
                                <div>
                                    <h5 class="card-title">Receitas Recebidas</h5>
                                    <p class="card-value">R$ {{ number_format($totalReceitasRecebidas, 2, ',', '.') }}</p>
                                    <p class="card-subtitle">{{ $receitasRecebidas }} registros</p>
                                </div>
                                <div class="card-icon">✅</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="metric-card warning">
                            <div class="card-header">
                                <div>
                                    <h5 class="card-title">Receitas Pendentes</h5>
                                    <p class="card-value">R$ {{ number_format($totalReceitasPendentes, 2, ',', '.') }}</p>
                                    <p class="card-subtitle">{{ $receitasPendentes }} registros</p>
                                </div>
                                <div class="card-icon">⏳</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="metric-card danger">
                            <div class="card-header">
                                <div>
                                    <h5 class="card-title">Despesas Pendentes</h5>
                                    <p class="card-value">R$ {{ number_format($totalDespesasPendentes, 2, ',', '.') }}</p>
                                    <p class="card-subtitle">{{ $despesasPendentes }} registros</p>
                                </div>
                                <div class="card-icon">⚠️</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="metric-card info">
                            <div class="card-header">
                                <div>
                                    <h5 class="card-title">Taxa de Recebimento</h5>
                                    <p class="card-value">
                                        @php
                                            $totalReceitasCount = $receitasPendentes + $receitasRecebidas;
                                            $percentual = $totalReceitasCount > 0 ? ($receitasRecebidas / $totalReceitasCount) * 100 : 0;
                                        @endphp
                                        {{ number_format($percentual, 1) }}%
                                    </p>
                                    <p class="card-subtitle">Performance</p>
                                </div>
                                <div class="card-icon">📈</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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
    </script>
</x-admin.layout>