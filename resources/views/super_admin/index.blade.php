<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin — Painel</title>
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
        .stat-card { background: #fff; border-radius: 12px; padding: 24px; border: 1px solid #e9ecf0; }
        .stat-card .number { font-size: 2rem; font-weight: 800; color: #1a1f36; }
        .stat-card .label { font-size: 0.8rem; color: #8892b0; text-transform: uppercase; letter-spacing: .05em; }
        .mod-card { background: #fff; border-radius: 12px; padding: 20px 24px; border: 1px solid #e9ecf0; display: flex; align-items: center; gap: 16px; }
        .mod-icon { width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; }
        .badge-status-ativo    { background: #d1fae5; color: #065f46; }
        .badge-status-inativo  { background: #fee2e2; color: #991b1b; }
        .badge-status-vencido  { background: #fef3c7; color: #92400e; }
        .section-title { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: #8892b0; margin-bottom: 12px; }
    </style>
</head>
<body>

{{-- Sidebar --}}
<div class="sidebar">
    <div class="logo">
        <i class="fas fa-layer-group me-2"></i> Super Admin
        <small>{{ auth()->user()->nome ?? auth()->user()->email }}</small>
    </div>
    <nav class="mt-3">
        <a href="{{ route('super.admin.index') }}" class="nav-link active">
            <i class="fas fa-chart-pie"></i> Dashboard
        </a>
        <a href="{{ route('super.admin.clientes') }}" class="nav-link">
            <i class="fas fa-building"></i> Clientes
        </a>
        <a href="{{ route('home') }}" class="nav-link">
            <i class="fas fa-arrow-left"></i> Voltar ao sistema
        </a>
    </nav>
</div>

{{-- Main --}}
<div class="main">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0 fw-bold">Dashboard</h4>
            <small class="text-muted">Visão geral de todos os clientes</small>
        </div>
        <a href="{{ route('super.admin.clientes') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-building me-1"></i> Ver todos os clientes
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Totais --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="label">Total de clientes</div>
                <div class="number">{{ $totais['clientes'] }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="label">Ativos</div>
                <div class="number text-success">{{ $totais['ativos'] }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="label">Vencidos</div>
                <div class="number text-danger">{{ $totais['vencidos'] }}</div>
            </div>
        </div>
    </div>

    {{-- Por modalidade --}}
    <div class="section-title">Clientes por modalidade</div>
    <div class="row g-3 mb-5">
        @php
            $icones = ['Surf' => '🏄', 'Pilates' => '🧘', 'Boxe' => '🥊', 'Corrida' => '🏃', 'Futevôlei' => '🏐', 'BodyBoard' => '🌊', 'Passeios' => '⛵'];
            $cores  = ['Surf' => '#dbeafe', 'Pilates' => '#d1fae5', 'Boxe' => '#fee2e2', 'Corrida' => '#fef3c7', 'Futevôlei' => '#ede9fe', 'BodyBoard' => '#cffafe', 'Passeios' => '#fce7f3'];
        @endphp
        @foreach($modalidades as $mod)
        <div class="col-md-4">
            <a href="{{ route('super.admin.clientes', ['modalidade' => $mod['id']]) }}" class="text-decoration-none">
                <div class="mod-card">
                    <div class="mod-icon" style="background: {{ $cores[$mod['nome']] ?? '#f1f5f9' }}">
                        {{ $icones[$mod['nome']] ?? '🏢' }}
                    </div>
                    <div>
                        <div class="fw-bold text-dark">{{ $mod['nome'] }}</div>
                        <div class="text-muted" style="font-size:.83rem">
                            {{ $mod['total'] }} cliente(s) · {{ $mod['ativos'] }} ativo(s)
                        </div>
                    </div>
                    <i class="fas fa-chevron-right ms-auto text-muted" style="font-size:.75rem"></i>
                </div>
            </a>
        </div>
        @endforeach
    </div>

    {{-- Cadastros recentes --}}
    <div class="section-title">Cadastros recentes</div>
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Empresa</th>
                        <th>Modalidade</th>
                        <th>Domínio</th>
                        <th>Status</th>
                        <th>Vencimento</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentes as $e)
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $e->nome }}</div>
                            <small class="text-muted">{{ $e->user?->email }}</small>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark">
                                {{ $icones[$e->modalidade?->nome] ?? '' }} {{ $e->modalidade?->nome ?? '—' }}
                            </span>
                        </td>
                        <td>
                            @if($e->site?->dominio_personalizado)
                                <small><i class="fas fa-globe me-1 text-primary"></i>{{ $e->site->dominio_personalizado }}</small>
                            @elseif($e->site?->slug)
                                <small class="text-muted">/site/{{ $e->site->slug }}</small>
                            @else
                                <small class="text-muted">—</small>
                            @endif
                        </td>
                        <td>
                            @php
                                $vencido = $e->data_vencimento && $e->data_vencimento->isPast();
                                $cls = $vencido ? 'badge-status-vencido' : ($e->status === 'ativo' ? 'badge-status-ativo' : 'badge-status-inativo');
                                $label = $vencido ? 'Vencido' : ucfirst($e->status ?? 'inativo');
                            @endphp
                            <span class="badge {{ $cls }} rounded-pill px-3 py-1">{{ $label }}</span>
                        </td>
                        <td>
                            {{ $e->data_vencimento?->format('d/m/Y') ?? '—' }}
                        </td>
                        <td>
                            <a href="{{ route('super.admin.show', $e) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Nenhum cliente cadastrado.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
