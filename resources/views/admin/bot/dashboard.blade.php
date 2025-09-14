<x-admin.layout title="Dashboard do Bot">
    <div class="page-wrapper">
        <div class="content container-fluid" style="padding: 2rem">
            
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold text-dark mb-1">
                        <i class="fas fa-robot text-primary me-2"></i>
                        Dashboard do Bot
                    </h2>
                    <p class="text-muted mb-0">Acompanhe o desempenho e métricas dos seus bots em tempo real</p>
                </div>
                <div class="text-end">
                    <small class="text-muted d-block">Última atualização</small>
                    <strong>{{ now()->format('d/m/Y H:i') }}</strong>
                </div>
            </div>
            <!-- /Page Header -->
            
            <x-alert/>

            <!-- Cards de Métricas -->
            <div class="row g-4 mb-5">
                <!-- Bots Ativos -->
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div class="metric-card card-active h-100">
                        <div class="metric-icon">
                            <i class="fas fa-robot"></i>
                        </div>
                        <div class="metric-content">
                            <h3 class="metric-number">{{ $botsAtivos }}</h3>
                            <p class="metric-label">Bots Ativos</p>
                            <div class="metric-trend">
                                <i class="fas fa-arrow-up"></i>
                                <span>Online</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tokens Consumidos -->
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div class="metric-card card-tokens h-100">
                        <div class="metric-icon">
                            <i class="fas fa-microchip"></i>
                        </div>
                        <div class="metric-content">
                            <h3 class="metric-number">{{ number_format($totalTokens, 0, ',', '.') }}</h3>
                            <p class="metric-label">Tokens Consumidos</p>
                            <div class="metric-trend">
                                <i class="fas fa-chart-line"></i>
                                <span>Total</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Custo Estimado -->
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div class="metric-card card-cost h-100">
                        <div class="metric-icon">
                            <i class="fas fa-calculator"></i>
                        </div>
                        <div class="metric-content">
                            <h3 class="metric-number">R$ {{ number_format($custoEstimado, 2, ',', '.') }}</h3>
                            <p class="metric-label">Custo Estimado</p>
                            <div class="metric-trend">
                                <i class="fas fa-chart-bar"></i>
                                <span>Estimativa</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Valor Cobrado -->
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div class="metric-card card-revenue h-100">
                        <div class="metric-icon">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <div class="metric-content">
                            <h3 class="metric-number">R$ {{ number_format($totalCusto ?? 0, 2, ',', '.') }}</h3>
                            <p class="metric-label">Valor Cobrado</p>
                            <div class="metric-trend">
                                <i class="fas fa-money-bill-wave"></i>
                                <span>Real</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Conversas Hoje -->
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div class="metric-card card-conversations h-100">
                        <div class="metric-icon">
                            <i class="fas fa-comments"></i>
                        </div>
                        <div class="metric-content">
                            <h3 class="metric-number">{{ $conversasHoje }}</h3>
                            <p class="metric-label">Conversas Hoje</p>
                            <div class="metric-trend">
                                <i class="fas fa-calendar-day"></i>
                                <span>{{ now()->format('d/m') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Eficiência -->
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div class="metric-card card-efficiency h-100">
                        <div class="metric-icon">
                            <i class="fas fa-tachometer-alt"></i>
                        </div>
                        <div class="metric-content">
                            <h3 class="metric-number">{{ $conversasHoje > 0 ? number_format(($totalTokens / $conversasHoje), 0) : '0' }}</h3>
                            <p class="metric-label">Tokens/Conversa</p>
                            <div class="metric-trend">
                                <i class="fas fa-gauge"></i>
                                <span>Média</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <!-- Gráfico de Tokens -->
                <div class="col-lg-8">
                    <div class="card shadow-lg border-0 h-100">
                        <div class="card-header bg-gradient-primary text-white py-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h5 class="card-title mb-0 fw-bold">
                                        <i class="fas fa-chart-line me-2"></i>
                                        Consumo de Tokens
                                    </h5>
                                    <small class="opacity-75">Últimos 7 dias</small>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-filter me-1"></i>Filtros
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#">7 dias</a></li>
                                        <li><a class="dropdown-item" href="#">30 dias</a></li>
                                        <li><a class="dropdown-item" href="#">90 dias</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div style="position: relative; height: 350px;">
                                <canvas id="tokensChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status dos Bots -->
                <div class="col-lg-4">
                    <div class="card shadow-lg border-0 h-100">
                        <div class="card-header bg-gradient-success text-white py-3">
                            <h5 class="card-title mb-0 fw-bold">
                                <i class="fas fa-heartbeat me-2"></i>
                                Status dos Bots
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="status-dot bg-success me-3"></div>
                                        <span>Bots Online</span>
                                    </div>
                                    <span class="badge bg-success rounded-pill">{{ $botsAtivos }}</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="status-dot bg-warning me-3"></div>
                                        <span>Em Manutenção</span>
                                    </div>
                                    <span class="badge bg-warning rounded-pill">0</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="status-dot bg-danger me-3"></div>
                                        <span>Offline</span>
                                    </div>
                                    <span class="badge bg-danger rounded-pill">0</span>
                                </div>
                            </div>
                            <div class="p-3 bg-light">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Monitoramento em tempo real
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Serviços do Bot -->
            <div class="card shadow-lg border-0 mt-4">
                <div class="card-header bg-gradient-info text-white py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="card-title mb-0 fw-bold">
                                <i class="fas fa-cogs me-2"></i>
                                Serviços Ativos
                            </h5>
                            <small class="opacity-75">{{ count($services) }} serviços cadastrados</small>
                        </div>
                        <a href="{{ route('admin.botservice.create') }}" class="btn btn-sm btn-outline-light">
                            <i class="fas fa-plus me-1"></i>Novo Serviço
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if(count($services) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0 fw-bold">
                                            <i class="fas fa-tag text-primary me-2"></i>
                                            Serviço
                                        </th>
                                        <th class="border-0 fw-bold">
                                            <i class="fas fa-chalkboard-teacher text-warning me-2"></i>
                                            Professor
                                        </th>
                                        <th class="border-0 fw-bold">
                                            <i class="fas fa-clock text-info me-2"></i>
                                            Horário
                                        </th>
                                        <th class="border-0 fw-bold text-end">
                                            <i class="fas fa-dollar-sign text-success me-2"></i>
                                            Valor
                                        </th>
                                        <th class="border-0 fw-bold text-center">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($services as $service)
                                    <tr class="service-row">
                                        <td class="align-middle">
                                            <div class="d-flex align-items-center">
                                                <div class="service-avatar bg-primary text-white rounded-circle me-3">
                                                    <i class="fas fa-cog"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-bold">{{ $service->nome_servico }}</h6>
                                                    <small class="text-muted">ID: #{{ $service->id }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            @if($service->professor)
                                                <span class="badge bg-light text-dark">{{ $service->professor }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            @if($service->horario)
                                                <small class="text-muted">{{ $service->horario }}</small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="align-middle text-end">
                                            <span class="fw-bold text-success fs-6">
                                                R$ {{ number_format($service->valor, 2, ',', '.') }}
                                            </span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('admin.botservice.edit', $service->id) }}" 
                                                   class="btn btn-outline-primary btn-sm" 
                                                   title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button class="btn btn-outline-danger btn-sm" 
                                                        title="Excluir"
                                                        onclick="confirmDelete({{ $service->id }})">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-robot text-muted mb-3" style="font-size: 3rem;"></i>
                            <h5 class="text-muted">Nenhum serviço cadastrado</h5>
                            <p class="text-muted mb-4">Comece criando seu primeiro serviço de bot</p>
                            <a href="{{ route('admin.botservice.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Criar Primeiro Serviço
                            </a>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <!-- Estilos CSS customizados -->
    <style>
        /* Gradientes */
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .bg-gradient-success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }
        
        .bg-gradient-info {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        /* Cards de Métricas */
        .metric-card {
            background: white;
            border: none;
            border-radius: 20px;
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            cursor: pointer;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }

        .metric-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.15);
        }

        .metric-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(45deg, #667eea, #764ba2);
        }

        .card-active::before { background: linear-gradient(45deg, #11998e, #38ef7d); }
        .card-tokens::before { background: linear-gradient(45deg, #667eea, #764ba2); }
        .card-cost::before { background: linear-gradient(45deg, #f093fb, #f5576c); }
        .card-revenue::before { background: linear-gradient(45deg, #4facfe, #00f2fe); }
        .card-conversations::before { background: linear-gradient(45deg, #43e97b, #38f9d7); }
        .card-efficiency::before { background: linear-gradient(45deg, #fa709a, #fee140); }

        .metric-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 60px;
            height: 60px;
            border-radius: 16px;
            margin-bottom: 1rem;
            font-size: 1.5rem;
            color: white;
        }

        .card-active .metric-icon { background: linear-gradient(45deg, #11998e, #38ef7d); }
        .card-tokens .metric-icon { background: linear-gradient(45deg, #667eea, #764ba2); }
        .card-cost .metric-icon { background: linear-gradient(45deg, #f093fb, #f5576c); }
        .card-revenue .metric-icon { background: linear-gradient(45deg, #4facfe, #00f2fe); }
        .card-conversations .metric-icon { background: linear-gradient(45deg, #43e97b, #38f9d7); }
        .card-efficiency .metric-icon { background: linear-gradient(45deg, #fa709a, #fee140); }

        .metric-number {
            font-size: 2rem;
            font-weight: 700;
            margin: 0.5rem 0;
            color: #2d3436;
        }

        .metric-label {
            font-size: 0.875rem;
            color: #636e72;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .metric-trend {
            display: flex;
            align-items: center;
            font-size: 0.75rem;
            color: #00b894;
            font-weight: 500;
        }

        .metric-trend i {
            margin-right: 0.25rem;
        }

        /* Status Dots */
        .status-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: inline-block;
        }

        /* Service Row */
        .service-row:hover {
            background-color: #f8f9fa;
        }

        .service-avatar {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Cards principais */
        .card {
            border: none;
            border-radius: 15px;
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .content {
                padding: 1rem !important;
            }
            
            .metric-card {
                padding: 1rem;
                text-align: center;
            }
            
            .metric-number {
                font-size: 1.5rem;
            }
        }

        /* Animações */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .metric-card {
            animation: fadeInUp 0.6s ease-out;
        }
    </style>

    @section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Configuração do gráfico
        const ctx = document.getElementById('tokensChart');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($labels),
                datasets: [{
                    label: 'Tokens Consumidos',
                    data: @json($dataTokens),
                    borderWidth: 3,
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#667eea',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        borderColor: '#667eea',
                        borderWidth: 1,
                        cornerRadius: 10,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return 'Tokens: ' + context.parsed.y.toLocaleString('pt-BR');
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#6c757d'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(108, 117, 125, 0.1)'
                        },
                        ticks: {
                            color: '#6c757d',
                            callback: function(value) {
                                return value.toLocaleString('pt-BR');
                            }
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });

        // Função para confirmar exclusão
        function confirmDelete(serviceId) {
            if (confirm('Tem certeza que deseja excluir este serviço?')) {
                // Implementar lógica de exclusão
                console.log('Excluindo serviço:', serviceId);
            }
        }

        // Animação dos números nas métricas
        document.addEventListener('DOMContentLoaded', function() {
            const metricNumbers = document.querySelectorAll('.metric-number');
            
            metricNumbers.forEach(number => {
                const finalValue = parseInt(number.textContent.replace(/\D/g, ''));
                const duration = 2000;
                const increment = finalValue / (duration / 16);
                let current = 0;
                
                const updateNumber = () => {
                    current += increment;
                    if (current < finalValue) {
                        number.textContent = Math.floor(current).toLocaleString('pt-BR');
                        requestAnimationFrame(updateNumber);
                    } else {
                        number.textContent = finalValue.toLocaleString('pt-BR');
                    }
                };
                
                setTimeout(() => updateNumber(), Math.random() * 500);
            });
        });
    </script>
    @endsection
</x-admin.layout>