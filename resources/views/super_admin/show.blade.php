<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin — {{ $empresa->nome }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f4f6fb; font-family: 'Segoe UI', sans-serif; }
        .sidebar { width: 240px; min-height: 100vh; background: #1a1f36; position: fixed; top: 0; left: 0; }
        .sidebar .logo { padding: 28px 24px 16px; color: #fff; font-size: 1.1rem; font-weight: 700; border-bottom: 1px solid #2d3354; }
        .sidebar .logo small { display: block; font-size: 0.7rem; font-weight: 400; color: #8892b0; margin-top: 2px; }
        .sidebar .nav-link { color: #8892b0; padding: 10px 24px; font-size: 0.88rem; display: flex; align-items: center; gap: 10px; }
        .sidebar .nav-link:hover { color: #fff; background: #2d3354; }
        .sidebar .nav-link i { width: 16px; }
        .main { margin-left: 240px; padding: 32px; }
        .info-card { background: #fff; border-radius: 12px; border: 1px solid #e9ecf0; padding: 24px; }
        .info-label { font-size: 0.75rem; text-transform: uppercase; letter-spacing: .06em; color: #8892b0; font-weight: 600; }
        .info-value { font-size: 0.95rem; color: #1a1f36; font-weight: 500; margin-top: 2px; }
        .badge-status-ativo   { background: #d1fae5; color: #065f46; }
        .badge-status-inativo { background: #fee2e2; color: #991b1b; }
        .badge-status-vencido { background: #fef3c7; color: #92400e; }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="logo">
        <i class="fas fa-layer-group me-2"></i> Super Admin
        <small>{{ auth()->user()->nome ?? auth()->user()->email }}</small>
    </div>
    <nav class="mt-3">
        <a href="{{ route('super.admin.index') }}" class="nav-link">
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

<div class="main">
    @php
        $icones = ['Surf' => '🏄', 'Pilates' => '🧘', 'Boxe' => '🥊', 'Corrida' => '🏃', 'Futevôlei' => '🏐', 'BodyBoard' => '🌊', 'Passeios' => '⛵'];
        $vencido = $empresa->data_vencimento && $empresa->data_vencimento->isPast();
        $statusCls = $vencido ? 'badge-status-vencido' : ($empresa->status === 'ativo' ? 'badge-status-ativo' : 'badge-status-inativo');
        $statusLabel = $vencido ? 'Vencido' : ucfirst($empresa->status ?? 'inativo');
    @endphp

    {{-- Header --}}
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('super.admin.clientes') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h4 class="mb-0 fw-bold">
                {{ $icones[$empresa->modalidade?->nome] ?? '🏢' }} {{ $empresa->nome }}
            </h4>
            <small class="text-muted">{{ $empresa->modalidade?->nome }} · ID #{{ $empresa->id }}</small>
        </div>
        <span class="badge {{ $statusCls }} rounded-pill px-3 py-2 ms-2">{{ $statusLabel }}</span>

        <form method="POST" action="{{ route('super.admin.toggle', $empresa) }}" class="ms-auto">
            @csrf @method('PATCH')
            <button class="btn btn-sm {{ $empresa->status === 'ativo' ? 'btn-outline-danger' : 'btn-outline-success' }}">
                <i class="fas fa-{{ $empresa->status === 'ativo' ? 'ban' : 'check' }} me-1"></i>
                {{ $empresa->status === 'ativo' ? 'Desativar' : 'Ativar' }}
            </button>
        </form>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row g-4">

        {{-- Dados da empresa --}}
        <div class="col-md-6">
            <div class="info-card h-100">
                <div class="fw-bold mb-3"><i class="fas fa-building me-2 text-primary"></i>Dados da empresa</div>
                <div class="row g-3">
                    <div class="col-6">
                        <div class="info-label">Nome</div>
                        <div class="info-value">{{ $empresa->nome }}</div>
                    </div>
                    <div class="col-6">
                        <div class="info-label">Modalidade</div>
                        <div class="info-value">{{ $empresa->modalidade?->nome ?? '—' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="info-label">CNPJ</div>
                        <div class="info-value">{{ $empresa->cnpj ?? '—' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="info-label">Telefone</div>
                        <div class="info-value">{{ $empresa->telefone ?? '—' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="info-label">Vencimento</div>
                        <div class="info-value {{ $vencido ? 'text-danger' : '' }}">
                            {{ $empresa->data_vencimento?->format('d/m/Y') ?? '—' }}
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="info-label">Valor aula</div>
                        <div class="info-value">
                            @if($empresa->valor_aula_de || $empresa->valor_aula_ate)
                                R$ {{ number_format($empresa->valor_aula_de, 2, ',', '.') }}
                                @if($empresa->valor_aula_ate)
                                    — R$ {{ number_format($empresa->valor_aula_ate, 2, ',', '.') }}
                                @endif
                            @else
                                —
                            @endif
                        </div>
                    </div>
                    @if($empresa->endereco)
                    <div class="col-12">
                        <div class="info-label">Endereço</div>
                        <div class="info-value">
                            {{ $empresa->endereco->endereco }}, {{ $empresa->endereco->cidade }} — {{ $empresa->endereco->uf }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Dono da conta --}}
        <div class="col-md-6">
            <div class="info-card h-100">
                <div class="fw-bold mb-3"><i class="fas fa-user me-2 text-primary"></i>Responsável pela conta</div>
                <div class="row g-3">
                    <div class="col-12">
                        <div class="info-label">Nome</div>
                        <div class="info-value">{{ $empresa->user?->nome ?? '—' }}</div>
                    </div>
                    <div class="col-12">
                        <div class="info-label">E-mail</div>
                        <div class="info-value">{{ $empresa->user?->email ?? '—' }}</div>
                    </div>
                    <div class="col-12">
                        <div class="info-label">Telefone</div>
                        <div class="info-value">{{ $empresa->user?->telefone ?? '—' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="info-label">Cadastrado em</div>
                        <div class="info-value">{{ $empresa->created_at?->format('d/m/Y') }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Site --}}
        <div class="col-md-6">
            <div class="info-card">
                <div class="fw-bold mb-3"><i class="fas fa-globe me-2 text-primary"></i>Site</div>
                @if($empresa->site)
                <div class="row g-3">
                    <div class="col-6">
                        <div class="info-label">Slug</div>
                        <div class="info-value">
                            <a href="{{ route('site.publico', $empresa->site->slug) }}" target="_blank">
                                /site/{{ $empresa->site->slug }}
                            </a>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="info-label">Domínio personalizado</div>
                        <div class="info-value">
                            @if($empresa->site->dominio_personalizado)
                                <a href="http://{{ $empresa->site->dominio_personalizado }}" target="_blank">
                                    {{ $empresa->site->dominio_personalizado }}
                                </a>
                            @else
                                <span class="text-muted">Não configurado</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="info-label">Template</div>
                        <div class="info-value">{{ $empresa->site->template?->titulo ?? '—' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="info-label">Status do site</div>
                        <div class="info-value">{{ $empresa->site->ativo ? '✅ Ativo' : '❌ Inativo' }}</div>
                    </div>
                </div>
                @else
                    <p class="text-muted mb-0">Site não configurado.</p>
                @endif
            </div>
        </div>

        {{-- Planos --}}
        <div class="col-md-6">
            <div class="info-card">
                <div class="fw-bold mb-3"><i class="fas fa-credit-card me-2 text-primary"></i>Planos</div>
                @forelse($empresa->planos as $plano)
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                    <div>
                        <div class="fw-semibold">{{ $plano->nome }}</div>
                        <small class="text-muted">{{ $plano->periodicidade }} · R$ {{ number_format($plano->valor, 2, ',', '.') }}</small>
                    </div>
                    <small class="text-muted">
                        {{ \Carbon\Carbon::parse($plano->pivot->data_inicio)->format('d/m/Y') }}
                        @if($plano->pivot->data_fim)
                            → {{ \Carbon\Carbon::parse($plano->pivot->data_fim)->format('d/m/Y') }}
                        @endif
                    </small>
                </div>
                @empty
                    <p class="text-muted mb-0">Nenhum plano vinculado.</p>
                @endforelse
            </div>
        </div>

        {{-- Resumo operacional --}}
        <div class="col-12">
            <div class="info-card">
                <div class="fw-bold mb-3"><i class="fas fa-chart-bar me-2 text-primary"></i>Resumo operacional</div>
                <div class="row g-3 text-center">
                    <div class="col-md-3">
                        <div class="fs-3 fw-bold text-primary">{{ $empresa->professores->count() }}</div>
                        <div class="text-muted small">Professores</div>
                    </div>
                    <div class="col-md-3">
                        <div class="fs-3 fw-bold text-primary">{{ $empresa->servicos->count() }}</div>
                        <div class="text-muted small">Serviços</div>
                    </div>
                    <div class="col-md-3">
                        <div class="fs-3 fw-bold text-primary">{{ $empresa->paymentGateways->count() }}</div>
                        <div class="text-muted small">Gateways de pagamento</div>
                    </div>
                    <div class="col-md-3">
                        <div class="fs-3 fw-bold text-primary">
                            {{ $empresa->site?->atendimento_com_ia ? '✅' : '—' }}
                        </div>
                        <div class="text-muted small">Atendimento IA</div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
