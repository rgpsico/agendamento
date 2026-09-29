<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin — Clientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f4f6fb; font-family: 'Segoe UI', sans-serif; }
        .sidebar { width: 240px; min-height: 100vh; background: #1a1f36; position: fixed; top: 0; left: 0; }
        .sidebar .logo { padding: 28px 24px 16px; color: #fff; font-size: 1.1rem; font-weight: 700; border-bottom: 1px solid #2d3354; }
        .sidebar .logo small { display: block; font-size: 0.7rem; font-weight: 400; color: #8892b0; margin-top: 2px; }
        .sidebar .nav-link { color: #8892b0; padding: 10px 24px; font-size: 0.88rem; display: flex; align-items: center; gap: 10px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background: #2d3354; }
        .sidebar .nav-link i { width: 16px; }
        .main { margin-left: 240px; padding: 32px; }
        .sidebar-toggle-btn { display: none; position: fixed; top: 14px; left: 16px; z-index: 1051; width: 42px; height: 42px; border-radius: 10px; background: #1a1f36; color: #fff; border: none; align-items: center; justify-content: center; font-size: 1.1rem; }
        .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.4); z-index: 1049; }
        @media (max-width: 991px) {
            .sidebar-toggle-btn { display: flex; }
            .sidebar { transform: translateX(-100%); transition: transform 0.25s ease; z-index: 1050; }
            .sidebar.open { transform: translateX(0); }
            .sidebar-overlay.show { display: block; }
            .main { margin-left: 0; padding: 76px 16px 20px; }
        }
        .badge-status-ativo    { background: #d1fae5; color: #065f46; }
        .badge-status-inativo  { background: #fee2e2; color: #991b1b; }
        .badge-status-vencido  { background: #fef3c7; color: #92400e; }
    </style>
</head>
<body>

<button class="sidebar-toggle-btn" type="button" onclick="document.querySelector('.sidebar').classList.toggle('open'); document.querySelector('.sidebar-overlay').classList.toggle('show');"><i class="fas fa-bars"></i></button>
<div class="sidebar-overlay" onclick="document.querySelector('.sidebar').classList.remove('open'); this.classList.remove('show');"></div>
<div class="sidebar">
    <div class="logo">
        <i class="fas fa-layer-group me-2"></i> Super Admin
        <small>{{ auth()->user()->nome ?? auth()->user()->email }}</small>
    </div>
    <nav class="mt-3">
        <a href="{{ route('super.admin.index') }}" class="nav-link">
            <i class="fas fa-chart-pie"></i> Dashboard
        </a>
        <a href="{{ route('super.admin.clientes') }}" class="nav-link active">
            <i class="fas fa-building"></i> Clientes
        </a>
        <a href="{{ route('home') }}" class="nav-link">
            <i class="fas fa-arrow-left"></i> Voltar ao sistema
        </a>
    </nav>
</div>

<div class="main">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0 fw-bold">Clientes</h4>
            <small class="text-muted">{{ $clientes->total() }} empresa(s) encontrada(s)</small>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Filtros --}}
    <form method="GET" class="card border-0 shadow-sm p-3 mb-4">
        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small fw-semibold mb-1">Buscar</label>
                <input type="text" name="busca" class="form-control form-control-sm"
                       placeholder="Nome ou e-mail..." value="{{ request('busca') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold mb-1">Modalidade</label>
                <select name="modalidade" class="form-select form-select-sm">
                    <option value="">Todas</option>
                    @foreach($modalidades as $m)
                        <option value="{{ $m->id }}" @selected(request('modalidade') == $m->id)>{{ $m->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold mb-1">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">Todos</option>
                    <option value="ativo"   @selected(request('status') === 'ativo')>Ativo</option>
                    <option value="inativo" @selected(request('status') === 'inativo')>Inativo</option>
                    <option value="vencido" @selected(request('status') === 'vencido')>Vencido</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm w-100">
                    <i class="fas fa-search me-1"></i> Filtrar
                </button>
                <a href="{{ route('super.admin.clientes') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </div>
    </form>

    {{-- Tabela --}}
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Empresa</th>
                        <th>Modalidade</th>
                        <th>Domínio / Slug</th>
                        <th>Professores</th>
                        <th>Serviços</th>
                        <th>Plano</th>
                        <th>Vencimento</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $icones = ['Surf' => '🏄', 'Pilates' => '🧘', 'Boxe' => '🥊', 'Corrida' => '🏃', 'Futevôlei' => '🏐', 'BodyBoard' => '🌊', 'Passeios' => '⛵'];
                    @endphp
                    @forelse($clientes as $e)
                    <tr>
                        <td class="text-muted small">{{ $e->id }}</td>
                        <td>
                            <div class="fw-semibold">{{ $e->nome }}</div>
                            <small class="text-muted">{{ $e->user?->email }}</small>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                {{ $icones[$e->modalidade?->nome] ?? '🏢' }} {{ $e->modalidade?->nome ?? '—' }}
                            </span>
                        </td>
                        <td>
                            @if($e->site?->dominio_personalizado)
                                <a href="http://{{ $e->site->dominio_personalizado }}" target="_blank" class="small">
                                    <i class="fas fa-globe me-1 text-primary"></i>{{ $e->site->dominio_personalizado }}
                                </a>
                            @elseif($e->site?->slug)
                                <small class="text-muted">/site/{{ $e->site->slug }}</small>
                            @else
                                <small class="text-muted">—</small>
                            @endif
                        </td>
                        <td class="text-center">{{ $e->professores_count }}</td>
                        <td class="text-center">{{ $e->servicos_count }}</td>
                        <td><small class="text-muted">—</small></td>
                        <td>
                            @if($e->data_vencimento)
                                <small class="{{ $e->data_vencimento->isPast() ? 'text-danger fw-semibold' : '' }}">
                                    {{ $e->data_vencimento->format('d/m/Y') }}
                                </small>
                            @else
                                <small class="text-muted">—</small>
                            @endif
                        </td>
                        <td>
                            @php
                                $vencido = $e->data_vencimento && $e->data_vencimento->isPast();
                                $cls   = $vencido ? 'badge-status-vencido' : ($e->status === 'ativo' ? 'badge-status-ativo' : 'badge-status-inativo');
                                $label = $vencido ? 'Vencido' : ucfirst($e->status ?? 'inativo');
                            @endphp
                            <span class="badge {{ $cls }} rounded-pill px-3 py-1">{{ $label }}</span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('super.admin.show', $e) }}"
                                   class="btn btn-sm btn-outline-secondary" title="Ver detalhes">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form method="POST" action="{{ route('super.admin.toggle', $e) }}">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-sm {{ $e->status === 'ativo' ? 'btn-outline-danger' : 'btn-outline-success' }}"
                                            title="{{ $e->status === 'ativo' ? 'Desativar' : 'Ativar' }}">
                                        <i class="fas fa-{{ $e->status === 'ativo' ? 'ban' : 'check' }}"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted py-5">Nenhum cliente encontrado.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($clientes->hasPages())
        <div class="card-footer bg-white border-top-0 pt-0 pb-3 px-3">
            {{ $clientes->links() }}
        </div>
        @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
