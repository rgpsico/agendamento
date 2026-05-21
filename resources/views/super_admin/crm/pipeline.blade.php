<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin — Pipeline</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f4f6fb; font-family: 'Segoe UI', sans-serif; }
        .sidebar { width: 240px; min-height: 100vh; background: #1a1f36; position: fixed; top: 0; left: 0; z-index: 100; }
        .sidebar .logo { padding: 28px 24px 16px; color: #fff; font-size: 1.1rem; font-weight: 700; border-bottom: 1px solid #2d3354; }
        .sidebar .logo small { display: block; font-size: 0.7rem; color: #8892b0; margin-top: 2px; }
        .sidebar .nav-link { color: #8892b0; padding: 10px 24px; font-size: 0.88rem; display: flex; align-items: center; gap: 10px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background: #2d3354; }
        .sidebar .nav-link i { width: 16px; }
        .sidebar .nav-section { padding: 16px 24px 4px; font-size: 0.68rem; text-transform: uppercase; letter-spacing: .1em; color: #4a5568; }
        .main { margin-left: 240px; padding: 28px 28px 28px; }
        .kanban-board { display: flex; gap: 16px; overflow-x: auto; padding-bottom: 16px; min-height: 70vh; }
        .kanban-col { min-width: 230px; max-width: 230px; background: #eef0f5; border-radius: 12px; padding: 12px; flex-shrink: 0; }
        .kanban-col-header { font-size: 0.78rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: #4a5568; margin-bottom: 10px; display: flex; justify-content: space-between; }
        .kanban-col-header .count { background: #fff; color: #1a1f36; border-radius: 100px; padding: 1px 8px; font-size: 0.72rem; }
        .lead-card { background: #fff; border-radius: 10px; padding: 12px; margin-bottom: 8px; border: 1px solid #e9ecf0; cursor: default; transition: box-shadow .15s; }
        .lead-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.08); }
        .lead-card .nome { font-weight: 700; font-size: 0.88rem; color: #1a1f36; }
        .lead-card .info { font-size: 0.75rem; color: #8892b0; margin-top: 3px; }
        .lead-card .actions { margin-top: 10px; display: flex; gap: 6px; }
        .nicho-badge { display: inline-flex; align-items: center; gap: 4px; padding: 2px 7px; border-radius: 100px; font-size: 0.7rem; font-weight: 600; }
        .nicho-surf    { background: #dbeafe; color: #1e40af; }
        .nicho-pilates { background: #d1fae5; color: #065f46; }
        .nicho-landing { background: #f3f4f6; color: #374151; }
        .col-novo_lead         .kanban-col-header { color: #3730a3; }
        .col-em_contato        .kanban-col-header { color: #92400e; }
        .col-aula_experimental .kanban-col-header { color: #9d174d; }
        .col-matriculado       .kanban-col-header { color: #065f46; }
        .col-recorrente        .kanban-col-header { color: #164e63; }
        .col-novo_lead         { border-top: 3px solid #818cf8; }
        .col-em_contato        { border-top: 3px solid #fbbf24; }
        .col-aula_experimental { border-top: 3px solid #f472b6; }
        .col-matriculado       { border-top: 3px solid #34d399; }
        .col-recorrente        { border-top: 3px solid #22d3ee; }
        .filter-bar { background: #fff; border-radius: 10px; padding: 10px 16px; border: 1px solid #e9ecf0; margin-bottom: 20px; display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
        .filter-bar label { font-size: .8rem; font-weight: 600; color: #4a5568; margin: 0; }
        .nicho-btn { padding: 4px 14px; border-radius: 100px; font-size: .78rem; font-weight: 600; border: 1.5px solid #e2e8f0; background: #fff; cursor: pointer; transition: all .15s; }
        .nicho-btn.active, .nicho-btn:hover { border-color: #6366f1; background: #eef2ff; color: #4338ca; }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="logo">
        <i class="fas fa-layer-group me-2"></i> Super Admin
        <small>{{ auth()->user()->email }}</small>
    </div>
    <nav class="mt-2">
        <a href="{{ route('super.admin.index') }}"    class="nav-link"><i class="fas fa-chart-pie"></i> Dashboard</a>
        <a href="{{ route('super.admin.clientes') }}" class="nav-link"><i class="fas fa-building"></i> Clientes</a>
        <div class="nav-section">CRM</div>
        <a href="{{ route('super.admin.crm.leads') }}"      class="nav-link"><i class="fas fa-users"></i> Leads</a>
        <a href="{{ route('super.admin.crm.pipeline') }}"   class="nav-link active"><i class="fas fa-columns"></i> Pipeline</a>
        <a href="{{ route('super.admin.crm.sequencias') }}" class="nav-link"><i class="fas fa-robot"></i> Sequências</a>
        <a href="{{ route('super.admin.crm.templates') }}"  class="nav-link"><i class="fas fa-envelope-open-text"></i> Templates</a>
        <a href="{{ route('home') }}" class="nav-link mt-3"><i class="fas fa-arrow-left"></i> Voltar</a>
    </nav>
</div>

<div class="main">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0 fw-bold">Pipeline de Leads</h4>
            <small class="text-muted">Leads das landing pages por estágio</small>
        </div>
        <a href="{{ route('super.admin.crm.leads') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-list me-1"></i> Ver lista
        </a>
    </div>

    {{-- Filtro por nicho --}}
    <div class="filter-bar">
        <label>Nicho:</label>
        @php $nichos = ['todos' => '🌐 Todos', 'surf' => '🏄 Surf', 'pilates' => '🧘 Pilates']; @endphp
        @foreach($nichos as $key => $label)
            <a href="{{ route('super.admin.crm.pipeline', ['nicho' => $key]) }}"
               class="nicho-btn {{ $nicho === $key ? 'active' : '' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    @if(session('success'))
        <div class="alert alert-success py-2 mb-3">{{ session('success') }}</div>
    @endif

    {{-- Kanban --}}
    <div class="kanban-board">
        @foreach($colunas as $status => $label)
        <div class="kanban-col col-{{ $status }}">
            <div class="kanban-col-header">
                {{ $label }}
                <span class="count">{{ count($pipeline[$status]) }}</span>
            </div>

            @forelse($pipeline[$status] as $lead)
            <div class="lead-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="nome">{{ $lead->nome }}</div>
                    <span class="nicho-badge nicho-{{ $lead->origem }}" style="margin-left:4px;flex-shrink:0">
                        @if($lead->origem === 'surf') 🏄
                        @elseif($lead->origem === 'pilates') 🧘
                        @else 🌐 @endif
                    </span>
                </div>
                <div class="info">{{ $lead->email }}</div>
                @if($lead->telefone)
                <div class="info"><i class="fab fa-whatsapp text-success"></i> {{ $lead->telefone }}</div>
                @endif
                <div class="info mt-1">{{ $lead->created_at->diffForHumans() }}</div>

                <div class="actions">
                    @if($lead->whatsapp_url)
                    <a href="{{ $lead->whatsapp_url }}" target="_blank"
                       class="btn btn-sm btn-success py-1 px-2" style="font-size:.72rem">
                        <i class="fab fa-whatsapp"></i> WhatsApp
                    </a>
                    @endif

                    {{-- Mover para próximo estágio --}}
                    @php
                        $keys = array_keys($colunas);
                        $idx  = array_search($status, $keys);
                        $prox = $keys[$idx + 1] ?? null;
                    @endphp
                    @if($prox)
                    <form method="POST" action="{{ route('super.admin.crm.mover', $lead) }}">
                        @csrf @method('PATCH')
                        <input type="hidden" name="pipeline_status" value="{{ $prox }}">
                        <button class="btn btn-sm btn-outline-primary py-1 px-2" style="font-size:.72rem"
                                title="Mover para {{ $colunas[$prox] }}">
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center text-muted py-4" style="font-size:.8rem">Vazio</div>
            @endforelse
        </div>
        @endforeach
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
