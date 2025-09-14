<x-admin.layout title="Despesas">
    <div class="page-wrapper">
        <div class="content container-fluid" style="padding: 2% 5%">

            <!-- Page Header -->
            <div class="page-header-animated">
                <x-header.titulo pageTitle="Despesas"/>
            </div>
            <!-- /Page Header -->

            <x-alert/>

            <div class="mb-4 text-end action-buttons">
                <a href="{{ route('financeiro.despesas.create') }}" class="btn btn-primary btn-animated">
                    <i class="fas fa-plus me-2"></i>
                    <span class="d-none d-sm-inline">Lançar</span> Despesa
                </a>
            </div>

            <!-- Filtros Responsivos -->
            <div class="filters-container mb-4">
                <div class="card shadow-sm">
                    <div class="card-body py-3">
                        <div class="row g-3">
                            <div class="col-12 col-md-6 col-lg-3">
                                <select class="form-select form-select-sm" id="statusFilter">
                                    <option value="">Todos os Status</option>
                                    <option value="PAID">Pago</option>
                                    <option value="PENDING">Pendente</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-6 col-lg-3">
                                <select class="form-select form-select-sm" id="categoriaFilter">
                                    <option value="">Todas as Categorias</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-6 col-lg-3">
                                <input type="date" class="form-control form-control-sm" id="dataFilter">
                            </div>
                            <div class="col-12 col-md-6 col-lg-3">
                                <input type="text" class="form-control form-control-sm" placeholder="Buscar..." id="searchFilter">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow table-card">
                <div class="card-body p-0">
                    <!-- Versão Desktop/Tablet -->
                    <div class="d-none d-md-block">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr class="header-row">
                                        <th class="border-0 ps-4">Descrição</th>
                                        <th class="border-0">Valor</th>
                                        <th class="border-0 d-none d-lg-table-cell">Categoria</th>
                                        <th class="border-0">Status</th>
                                        <th class="border-0 d-none d-xl-table-cell">Vencimento</th>
                                        <th class="border-0 d-none d-lg-table-cell">Empresa</th>
                                        <th class="border-0 d-none d-xl-table-cell">Usuário</th>
                                        <th class="border-0 text-center pe-4">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($despesas as $despesa)
                                        <tr class="table-row" data-status="{{ $despesa->status }}" data-categoria="{{ $despesa->categoria }}">
                                            <td class="ps-4">
                                                <div class="fw-medium">{{ $despesa->descricao }}</div>
                                                <div class="d-md-none d-lg-block">
                                                    <small class="text-muted d-lg-none">
                                                        {{ $despesa->categoria ?? '-' }} • 
                                                        {{ \Carbon\Carbon::parse($despesa->data_vencimento)->format('d/m/Y') }}
                                                    </small>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="fw-bold text-primary">R$ {{ $despesa->valor }}</span>
                                            </td>
                                            <td class="d-none d-lg-table-cell">
                                                <span class="badge bg-light text-dark">{{ $despesa->categoria ?? '-' }}</span>
                                            </td>
                                            <td>
                                                @if($despesa->status === 'PAID')
                                                    <span class="badge bg-success status-badge">
                                                        <i class="fas fa-check me-1"></i>Pago
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning text-dark status-badge">
                                                        <i class="fas fa-clock me-1"></i>Pendente
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="d-none d-xl-table-cell">
                                                <span class="text-muted">{{ \Carbon\Carbon::parse($despesa->data_vencimento)->format('d/m/Y') }}</span>
                                            </td>
                                            <td class="d-none d-lg-table-cell">
                                                <span class="text-muted">{{ $despesa->empresa->nome ?? '-' }}</span>
                                            </td>
                                            <td class="d-none d-xl-table-cell">
                                                <span class="text-muted">{{ $despesa->usuario->nome ?? '-' }}</span>
                                            </td>
                                            <td class="text-center pe-4">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="{{ route('financeiro.despesas.edit', $despesa->id) }}" 
                                                       class="btn btn-outline-warning btn-action" 
                                                       data-bs-toggle="tooltip" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('financeiro.despesas.destroy', $despesa->id) }}" 
                                                          method="POST" 
                                                          class="d-inline"
                                                          onsubmit="return confirm('Excluir esta despesa?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="btn btn-outline-danger btn-action"
                                                                data-bs-toggle="tooltip" title="Excluir">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr class="empty-row">
                                            <td colspan="8" class="text-center py-5">
                                                <div class="empty-state">
                                                    <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                                                    <h5 class="text-muted">Nenhuma despesa encontrada</h5>
                                                    <p class="text-muted mb-0">Comece criando sua primeira despesa</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Versão Mobile -->
                    <div class="d-block d-md-none mobile-cards">
                        @forelse($despesas as $despesa)
                            <div class="mobile-card" data-status="{{ $despesa->status }}" data-categoria="{{ $despesa->categoria }}">
                                <div class="card border-0 shadow-sm mb-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="card-title mb-1 fw-bold">{{ $despesa->descricao }}</h6>
                                            @if($despesa->status === 'PAID')
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check me-1"></i>Pago
                                                </span>
                                            @else
                                                <span class="badge bg-warning text-dark">
                                                    <i class="fas fa-clock me-1"></i>Pendente
                                                </span>
                                            @endif
                                        </div>
                                        
                                        <div class="mb-3">
                                            <span class="h5 text-primary fw-bold">R$ {{ $despesa->valor }}</span>
                                        </div>

                                        <div class="row g-2 text-sm mb-3">
                                            <div class="col-6">
                                                <small class="text-muted d-block">Categoria:</small>
                                                <span class="badge bg-light text-dark">{{ $despesa->categoria ?? '-' }}</span>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted d-block">Vencimento:</small>
                                                <span>{{ \Carbon\Carbon::parse($despesa->data_vencimento)->format('d/m/Y') }}</span>
                                            </div>
                                        </div>

                                        <div class="row g-2 text-sm mb-3">
                                            <div class="col-6">
                                                <small class="text-muted d-block">Empresa:</small>
                                                <span>{{ $despesa->empresa->nome ?? '-' }}</span>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted d-block">Usuário:</small>
                                                <span>{{ $despesa->usuario->nome ?? '-' }}</span>
                                            </div>
                                        </div>

                                        <div class="d-flex gap-2 justify-content-end">
                                            <a href="{{ route('financeiro.despesas.edit', $despesa->id) }}" 
                                               class="btn btn-warning btn-sm flex-fill">
                                                <i class="fas fa-edit me-1"></i>Editar
                                            </a>
                                            <form action="{{ route('financeiro.despesas.destroy', $despesa->id) }}" 
                                                  method="POST" 
                                                  class="flex-fill"
                                                  onsubmit="return confirm('Excluir esta despesa?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm w-100">
                                                    <i class="fas fa-trash me-1"></i>Excluir
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <div class="empty-state">
                                    <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">Nenhuma despesa encontrada</h5>
                                    <p class="text-muted mb-0">Comece criando sua primeira despesa</p>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <!-- Paginação -->
                    @if($despesas->hasPages())
                        <div class="card-footer bg-transparent border-top-0 pt-3">
                            <div class="pagination-container">
                                {{ $despesas->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <!-- CSS Customizado -->
    <style>
        :root {
            --primary-color: #4f46e5;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --dark-color: #1f2937;
            --light-color: #f8fafc;
            --border-radius: 12px;
            --transition: all 0.3s ease;
        }

        .page-wrapper {
            min-height: 100vh;
        }

        .btn-animated {
            position: relative;
            overflow: hidden;
            transition: var(--transition);
            border-radius: var(--border-radius);
        }

        .btn-animated:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .table-card {
            border-radius: var(--border-radius);
            border: none;
            overflow: hidden;
        }

        .table-responsive {
            border-radius: var(--border-radius);
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            font-weight: 600;
            color: var(--dark-color);
            padding: 1rem 0.75rem;
            border: none;
        }

        .table tbody tr {
            transition: var(--transition);
            opacity: 0;
            transform: translateY(20px);
        }

        .table tbody tr:hover {
            background-color: rgba(79, 70, 229, 0.05);
            transform: scale(1.01);
        }

        .status-badge {
            border-radius: 50px;
            padding: 0.5rem 1rem;
            font-weight: 500;
            font-size: 0.8rem;
        }

        .btn-action {
            border-radius: 8px;
            transition: var(--transition);
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-action:hover {
            transform: scale(1.1);
        }

        .mobile-cards .mobile-card {
            opacity: 0;
            transform: translateX(-30px);
        }

        .filters-container {
            opacity: 0;
            transform: translateY(-20px);
        }

        .empty-state {
            opacity: 0;
            transform: scale(0.9);
        }

        .pagination-container {
            opacity: 0;
            transform: translateY(20px);
        }

        /* Breakpoints responsivos customizados */
        @media (max-width: 576px) {
            .content {
                padding: 3% 3% !important;
            }
            
            .btn-animated span {
                display: none !important;
            }
        }

        @media (max-width: 768px) {
            .table thead th:nth-child(3),
            .table tbody td:nth-child(3) {
                display: none;
            }
        }

        @media (max-width: 992px) {
            .table thead th:nth-child(6),
            .table tbody td:nth-child(6) {
                display: none;
            }
        }

        /* Animações customizadas */
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

        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* Loading states */
        .loading {
            opacity: 0.6;
            pointer-events: none;
        }

        .loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            margin: -10px 0 0 -10px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>

    <!-- Scripts GSAP -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar tooltips do Bootstrap
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Animações GSAP
            const tl = gsap.timeline();

            // Animar header
            tl.from('.page-header-animated', {
                duration: 0.8,
                y: -50,
                opacity: 0,
                ease: 'power3.out'
            });

            // Animar botão de ação
            tl.from('.action-buttons', {
                duration: 0.6,
                x: 50,
                opacity: 0,
                ease: 'power2.out'
            }, '-=0.4');

            // Animar filtros
            tl.from('.filters-container', {
                duration: 0.6,
                y: -30,
                opacity: 0,
                ease: 'power2.out'
            }, '-=0.3');

            // Animar card da tabela
            tl.from('.table-card', {
                duration: 0.8,
                y: 30,
                opacity: 0,
                ease: 'power2.out'
            }, '-=0.2');

            // Animar linhas da tabela (desktop)
            gsap.set('.table-row', { opacity: 0, y: 20 });
            gsap.to('.table-row', {
                duration: 0.5,
                opacity: 1,
                y: 0,
                stagger: 0.1,
                ease: 'power2.out',
                delay: 1.2
            });

            // Animar cards mobile
            gsap.set('.mobile-card', { opacity: 0, x: -30 });
            gsap.to('.mobile-card', {
                duration: 0.6,
                opacity: 1,
                x: 0,
                stagger: 0.15,
                ease: 'power2.out',
                delay: 1.2
            });

            // Animar estado vazio
            gsap.set('.empty-state', { opacity: 0, scale: 0.9 });
            gsap.to('.empty-state', {
                duration: 0.8,
                opacity: 1,
                scale: 1,
                ease: 'back.out(1.7)',
                delay: 1.5
            });

            // Animar paginação
            gsap.set('.pagination-container', { opacity: 0, y: 20 });
            gsap.to('.pagination-container', {
                duration: 0.6,
                opacity: 1,
                y: 0,
                ease: 'power2.out',
                delay: 1.8
            });

            // Hover effects para botões
            document.querySelectorAll('.btn-animated').forEach(btn => {
                btn.addEventListener('mouseenter', function() {
                    gsap.to(this, {
                        duration: 0.3,
                        scale: 1.05,
                        y: -3,
                        boxShadow: '0 8px 25px rgba(0,0,0,0.15)',
                        ease: 'power2.out'
                    });
                });

                btn.addEventListener('mouseleave', function() {
                    gsap.to(this, {
                        duration: 0.3,
                        scale: 1,
                        y: 0,
                        boxShadow: '0 2px 4px rgba(0,0,0,0.1)',
                        ease: 'power2.out'
                    });
                });
            });

            // Hover effects para linhas da tabela
            document.querySelectorAll('.table-row').forEach(row => {
                row.addEventListener('mouseenter', function() {
                    gsap.to(this, {
                        duration: 0.3,
                        backgroundColor: 'rgba(79, 70, 229, 0.05)',
                        scale: 1.01,
                        ease: 'power2.out'
                    });
                });

                row.addEventListener('mouseleave', function() {
                    gsap.to(this, {
                        duration: 0.3,
                        backgroundColor: 'transparent',
                        scale: 1,
                        ease: 'power2.out'
                    });
                });
            });

            // Filtros funcionais
            const statusFilter = document.getElementById('statusFilter');
            const categoriaFilter = document.getElementById('categoriaFilter');
            const searchFilter = document.getElementById('searchFilter');
            const dataFilter = document.getElementById('dataFilter');

            function filterRows() {
                const statusValue = statusFilter?.value || '';
                const categoriaValue = categoriaFilter?.value || '';
                const searchValue = searchFilter?.value.toLowerCase() || '';
                
                document.querySelectorAll('.table-row, .mobile-card').forEach(row => {
                    const status = row.getAttribute('data-status');
                    const categoria = row.getAttribute('data-categoria');
                    const text = row.textContent.toLowerCase();
                    
                    const matchStatus = !statusValue || status === statusValue;
                    const matchCategoria = !categoriaValue || categoria === categoriaValue;
                    const matchSearch = !searchValue || text.includes(searchValue);
                    
                    if (matchStatus && matchCategoria && matchSearch) {
                        gsap.to(row, { duration: 0.3, opacity: 1, display: 'table-row' });
                    } else {
                        gsap.to(row, { duration: 0.3, opacity: 0, display: 'none' });
                    }
                });
            }

            // Event listeners para filtros
            [statusFilter, categoriaFilter, searchFilter, dataFilter].forEach(filter => {
                if (filter) {
                    filter.addEventListener('change', filterRows);
                    filter.addEventListener('input', filterRows);
                }
            });

            // Animação de loading para forms
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', function() {
                    const btn = this.querySelector('button[type="submit"]');
                    if (btn) {
                        btn.classList.add('loading');
                        gsap.to(btn, { duration: 0.3, scale: 0.95 });
                    }
                });
            });

            // Parallax effect sutil no scroll
            gsap.registerPlugin(ScrollTrigger);
            
            gsap.utils.toArray('.table-card, .filters-container').forEach(element => {
                gsap.to(element, {
                    yPercent: -10,
                    ease: 'none',
                    scrollTrigger: {
                        trigger: element,
                        start: 'top bottom',
                        end: 'bottom top',
                        scrub: true
                    }
                });
            });
        });
    </script>
</x-admin.layout>