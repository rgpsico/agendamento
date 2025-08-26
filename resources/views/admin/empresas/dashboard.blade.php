<x-admin.layout title="Dashboard Moderno">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .content.container-fluid {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin: 20px;
        }

        /* Header Animations */
        .dashboard-header {
            text-align: center;
            margin-bottom: 40px;
            opacity: 0;
        }

        .dashboard-title {
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(45deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 10px;
        }

        /* Enhanced Cards */
        .card {
            background: white;
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            opacity: 0;
            transform: translateY(30px);
            position: relative;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        }

        /* Color Variants */
        .card.primary { --primary-color: #667eea; --secondary-color: #a0aeff; }
        .card.success { --primary-color: #28a745; --secondary-color: #71dd8a; }
        .card.danger { --primary-color: #dc3545; --secondary-color: #ff6b7a; }
        .card.warning { --primary-color: #ffc107; --secondary-color: #ffeb3b; }

        .card-body {
            padding: 30px;
        }

        .dash-widget-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .dash-widget-icon {
            width: 70px;
            height: 70px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            color: white;
            font-size: 28px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .dash-count h3 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #333;
            margin: 0;
            counter-reset: number-counter;
        }

        .dash-widget-info h6 {
            color: #666;
            font-weight: 600;
            margin-bottom: 15px;
            font-size: 1rem;
        }

        .progress {
            height: 8px;
            border-radius: 4px;
            background: rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .progress-bar {
            border-radius: 4px;
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            width: 0% !important;
            transition: width 2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Chart Cards */
        .card-chart {
            opacity: 0;
            transform: scale(0.9);
        }

        .card-chart .card-header {
            background: transparent;
            border-bottom: 2px solid #f8f9fa;
            padding: 25px 30px;
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #333;
            margin: 0;
        }

        .chart-container {
            position: relative;
            height: 350px;
            padding: 20px 0;
        }

        /* Modal Enhancements */
        .modal-content {
            border: none;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }

        .modal-header {
            background: linear-gradient(45deg, #dc3545, #ff6b7a);
            color: white;
            border: none;
            padding: 25px 30px;
        }

        .modal-title {
            font-weight: 600;
        }

        .modal-body {
            padding: 30px;
            font-size: 1.1rem;
            line-height: 1.6;
        }

        .modal-footer {
            border: none;
            padding: 20px 30px 30px;
        }

        .btn-primary {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            border-radius: 12px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }

        /* Responsive Grid */
        @media (max-width: 768px) {
            .content.container-fluid {
                margin: 10px;
                padding: 20px;
            }
            
            .dashboard-title {
                font-size: 2rem;
            }
            
            .card-body {
                padding: 20px;
            }
            
            .dash-count h3 {
                font-size: 2rem;
            }
        }

        /* Loading Animation */
        .loading-skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        /* Floating Elements */
        .floating-element {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            pointer-events: none;
        }
    </style>

    <!-- Enhanced CDN Links -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.2.0/chartjs-plugin-datalabels.min.js"></script>

    <!-- Modal (Enhanced) -->
    <div class="modal fade" id="empresaInativaModal" tabindex="-1" aria-labelledby="empresaInativaModalLabel"
        aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="empresaInativaModalLabel">
                        <i class="fas fa-exclamation-triangle me-2"></i>Sistema Bloqueado
                    </h5>
                </div>
                <div class="modal-body">
                    <p>Sua empresa está <strong>inativa</strong>. Para desbloquear o acesso ao sistema, é necessário
                        efetuar o pagamento do boleto pendente.</p>
                    <p>Por favor, clique no botão abaixo para acessar o boleto e regularizar sua situação.</p>
                </div>
                <div class="modal-footer">
                    <a href="{{ route('empresa.pagamento.boleto') }}" class="btn btn-primary">
                        <i class="fas fa-credit-card me-2"></i>Pagar Boleto
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="page-wrapper">
        <div class="content container-fluid">

            @include('admin.empresas._partials.header')

            <!-- Dashboard Header -->
            <div class="dashboard-header">
                <h1 class="dashboard-title">Dashboard Executivo</h1>
                <p class="text-muted">Visão geral do desempenho da empresa</p>
            </div>

            <!-- Stats Cards -->
            <div class="row">
                @php
                    $cards = [
                        ['Total de Alunos', $numeroTotalDeAlunos, 'fas fa-users', 'primary'],
                        ['Total Arrecadação', 'R$ ' . number_format($arrecadacao, 2, ',', '.'), 'fas fa-dollar-sign', 'success'],
                        ['Número de Aulas', $numero_total_de_aulas, 'fas fa-chalkboard-teacher', 'warning'],
                        ['Aulas Canceladas', $aulasCanceladas, 'fas fa-times-circle', 'danger']
                    ];
                @endphp

                @foreach ($cards as $index => $item)
                    <div class="col-xl-3 col-sm-6 col-12">
                        <div class="card {{ $item[3] }}" data-index="{{ $index }}">
                            <div class="card-body">
                                <div class="dash-widget-header">
                                    <div class="dash-widget-icon">
                                        <i class="{{ $item[2] }}"></i>
                                    </div>
                                    <div class="dash-count">
                                        <h3 class="counter" data-target="{{ is_numeric(str_replace(['R$ ', '.', ','], ['', '', '.'], $item[1])) ? (float)str_replace(['R$ ', '.', ','], ['', '', '.'], $item[1]) : (int)filter_var($item[1], FILTER_SANITIZE_NUMBER_INT) }}">0</h3>
                                    </div>
                                </div>
                                <div class="dash-widget-info">
                                    <h6 class="text-muted">{{ $item[0] }}</h6>
                                    <div class="progress progress-sm">
                                        <div class="progress-bar" data-width="{{ rand(60, 95) }}"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Charts Section -->
            <div class="row">
                <div class="col-md-12 col-lg-6">
                    <div class="card card-chart">
                        <div class="card-header">
                            <h4 class="card-title">
                                <i class="fas fa-chart-bar me-2"></i>Arrecadação por Período
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="revenueChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-12 col-lg-6">
                    <div class="card card-chart">
                        <div class="card-header">
                            <h4 class="card-title">
                                <i class="fas fa-chart-pie me-2"></i>Status das Aulas
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="statusChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Enhanced JavaScript -->
    <script>
        // GSAP Animations
        document.addEventListener('DOMContentLoaded', function() {
            // Create GSAP timeline
            const tl = gsap.timeline();

            // Animate header
            tl.fromTo('.dashboard-header', 
                { opacity: 0, y: -50 },
                { opacity: 1, y: 0, duration: 0.8, ease: "power2.out" }
            );

            // Animate cards with stagger
            tl.fromTo('.card:not(.card-chart)', 
                { opacity: 0, y: 30, scale: 0.9 },
                { 
                    opacity: 1, 
                    y: 0, 
                    scale: 1, 
                    duration: 0.6, 
                    stagger: 0.1,
                    ease: "back.out(1.7)" 
                },
                "-=0.4"
            );

            // Animate chart cards
            tl.fromTo('.card-chart', 
                { opacity: 0, scale: 0.8, rotationY: 15 },
                { 
                    opacity: 1, 
                    scale: 1, 
                    rotationY: 0,
                    duration: 0.8, 
                    stagger: 0.2,
                    ease: "power2.out" 
                },
                "-=0.3"
            );

            // Animate progress bars
            setTimeout(() => {
                $('.progress-bar').each(function() {
                    const width = $(this).data('width');
                    $(this).css('width', width + '%');
                });
            }, 1000);

            // Counter animation
            setTimeout(() => {
                $('.counter').each(function() {
                    const $this = $(this);
                    const target = parseFloat($this.data('target'));
                    
                    gsap.to($this[0], {
                        duration: 2,
                        innerHTML: target,
                        snap: { innerHTML: 1 },
                        ease: "power2.out",
                        onUpdate: function() {
                            const value = parseFloat(this.targets()[0].innerHTML);
                            if ($this.closest('.success').length) {
                                $this.text('R$ ' + value.toLocaleString('pt-BR', {minimumFractionDigits: 2}));
                            } else {
                                $this.text(Math.floor(value).toLocaleString('pt-BR'));
                            }
                        }
                    });
                });
            }, 500);

            // Floating elements animation
            function createFloatingElements() {
                for (let i = 0; i < 5; i++) {
                    const element = document.createElement('div');
                    element.className = 'floating-element';
                    element.style.width = Math.random() * 100 + 50 + 'px';
                    element.style.height = element.style.width;
                    element.style.left = Math.random() * 100 + '%';
                    element.style.top = Math.random() * 100 + '%';
                    document.body.appendChild(element);

                    gsap.to(element, {
                        x: 'random(-100, 100)',
                        y: 'random(-100, 100)',
                        rotation: 360,
                        duration: 'random(10, 20)',
                        repeat: -1,
                        yoyo: true,
                        ease: "sine.inOut"
                    });
                }
            }

            createFloatingElements();
        });

        // Enhanced Charts
        $(document).ready(function() {
            // Revenue Chart Data
            var arrecadacaoDatas = {!! json_encode(array_keys($arrecadacaoPorDia->toArray())) !!};
            var arrecadacaoValores = {!! json_encode(array_values($arrecadacaoPorDia->toArray())) !!};

            // Revenue Chart
            var ctx = document.getElementById('revenueChart').getContext('2d');
            var revenueChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: arrecadacaoDatas,
                    datasets: [{
                        label: 'Arrecadação (R$)',
                        data: arrecadacaoValores,
                        backgroundColor: function(context) {
                            const chart = context.chart;
                            const {ctx, chartArea} = chart;
                            if (!chartArea) return null;
                            
                            const gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
                            gradient.addColorStop(0, 'rgba(102, 126, 234, 0.1)');
                            gradient.addColorStop(1, 'rgba(102, 126, 234, 0.8)');
                            return gradient;
                        },
                        borderColor: 'rgba(102, 126, 234, 1)',
                        borderWidth: 2,
                        borderRadius: 8,
                        borderSkipped: false,
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
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            cornerRadius: 8,
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            callbacks: {
                                label: function(context) {
                                    return 'R$ ' + context.raw.toLocaleString('pt-BR', {minimumFractionDigits: 2});
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
                                color: '#666'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            ticks: {
                                color: '#666',
                                callback: function(value) {
                                    return 'R$ ' + value.toLocaleString('pt-BR');
                                }
                            }
                        }
                    },
                    animation: {
                        duration: 2000,
                        easing: 'easeInOutCubic'
                    }
                }
            });

            // Status Chart
            var aulasCanceladas = {{ $aulasCanceladas }};
            var aulasRealizadas = {{ $realizadas }};

            var ctx2 = document.getElementById('statusChart').getContext('2d');
            var statusChart = new Chart(ctx2, {
                type: 'doughnut',
                data: {
                    labels: ['Aulas Realizadas', 'Aulas Canceladas'],
                    datasets: [{
                        data: [aulasRealizadas, aulasCanceladas],
                        backgroundColor: [
                            'rgba(40, 167, 69, 0.8)',
                            'rgba(220, 53, 69, 0.8)'
                        ],
                        borderColor: [
                            'rgba(40, 167, 69, 1)',
                            'rgba(220, 53, 69, 1)'
                        ],
                        borderWidth: 3,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '60%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true,
                                font: {
                                    size: 12,
                                    weight: '500'
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            cornerRadius: 8,
                            titleColor: '#fff',
                            bodyColor: '#fff'
                        }
                    },
                    animation: {
                        animateRotate: true,
                        duration: 2000,
                        easing: 'easeInOutCubic'
                    }
                }
            });

            // Company Status Check
            $.ajax({
                url: "{{ route('empresa.verificarStatus', ['empresaId' => Auth::user()->empresa->id ?? '']) }}",
                method: "GET",
                success: function(response) {
                    if (response.status === 'inativo') {
                        // Animate modal entrance
                        $('#empresaInativaModal').modal('show');
                        
                        $('#empresaInativaModal').on('shown.bs.modal', function() {
                            gsap.fromTo('.modal-content', 
                                { scale: 0.7, opacity: 0 },
                                { scale: 1, opacity: 1, duration: 0.5, ease: "back.out(1.7)" }
                            );
                        });

                        // Disable sidebar
                        $('#sidebar').addClass('sidebar-disabled');
                        $('#sidebar a').each(function() {
                            $(this).attr('href', 'javascript:void(0);').css('cursor', 'not-allowed');
                        });
                    }
                },
                error: function(xhr) {
                    console.error('Erro ao verificar status da empresa:', xhr.responseText);
                }
            });
        });
    </script>

</x-admin.layout>