<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin — CRM Leads</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f4f6fb; font-family: 'Segoe UI', sans-serif; }
        .sidebar { width: 240px; min-height: 100vh; background: #1a1f36; position: fixed; top: 0; left: 0; }
        .sidebar .logo { padding: 28px 24px 16px; color: #fff; font-size: 1.1rem; font-weight: 700; border-bottom: 1px solid #2d3354; }
        .sidebar .logo small { display: block; font-size: 0.7rem; color: #8892b0; margin-top: 2px; }
        .sidebar .nav-link { color: #8892b0; padding: 10px 24px; font-size: 0.88rem; display: flex; align-items: center; gap: 10px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background: #2d3354; }
        .sidebar .nav-link i { width: 16px; }
        .sidebar .nav-section { padding: 16px 24px 4px; font-size: 0.68rem; text-transform: uppercase; letter-spacing: .1em; color: #4a5568; }
        .main { margin-left: 240px; padding: 32px; }
        .nicho-badge { display: inline-flex; align-items: center; gap: 5px; padding: 3px 10px; border-radius: 100px; font-size: 0.75rem; font-weight: 600; }
        .nicho-surf    { background: #dbeafe; color: #1e40af; }
        .nicho-pilates { background: #d1fae5; color: #065f46; }
        .nicho-landing { background: #f3f4f6; color: #374151; }
        .pipeline-badge { font-size: 0.72rem; padding: 3px 8px; border-radius: 100px; font-weight: 600; }
        .pipeline-novo_lead       { background: #e0e7ff; color: #3730a3; }
        .pipeline-em_contato      { background: #fef3c7; color: #92400e; }
        .pipeline-aula_experimental { background: #fce7f3; color: #9d174d; }
        .pipeline-matriculado     { background: #d1fae5; color: #065f46; }
        .pipeline-recorrente      { background: #cffafe; color: #164e63; }
        .temp-frio    { color: #93c5fd; }
        .temp-morno   { color: #fbbf24; }
        .temp-quente  { color: #f87171; }
        .stat-chip { background: #fff; border: 1px solid #e9ecf0; border-radius: 10px; padding: 14px 20px; }
        .stat-chip .num { font-size: 1.6rem; font-weight: 800; color: #1a1f36; }
        .stat-chip .lbl { font-size: 0.75rem; color: #8892b0; text-transform: uppercase; letter-spacing: .05em; }
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
        <a href="{{ route('super.admin.crm.leads') }}"      class="nav-link active"><i class="fas fa-users"></i> Leads</a>
        <a href="{{ route('super.admin.crm.pipeline') }}"   class="nav-link"><i class="fas fa-columns"></i> Pipeline</a>
        <a href="{{ route('super.admin.crm.sequencias') }}" class="nav-link"><i class="fas fa-robot"></i> Sequências</a>
        <a href="{{ route('super.admin.crm.templates') }}"  class="nav-link"><i class="fas fa-envelope-open-text"></i> Templates</a>
        <a href="{{ route('home') }}" class="nav-link mt-3"><i class="fas fa-arrow-left"></i> Voltar</a>
    </nav>
</div>

<div class="main">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0 fw-bold">CRM — Leads das landing pages</h4>
            <small class="text-muted">Leads que querem contratar o SaaS</small>
        </div>
        <a href="{{ route('super.admin.crm.pipeline') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-columns me-1"></i> Ver Pipeline
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success py-2">{{ session('success') }}</div>
    @endif

    {{-- Cards por nicho --}}
    <div class="row g-3 mb-4">
        @php
            $nichos = ['surf' => '🏄 Surf', 'pilates' => '🧘 Pilates', 'landing' => '🌐 Outros'];
        @endphp
        <div class="col-md-3">
            <div class="stat-chip">
                <div class="lbl">Total</div>
                <div class="num">{{ $totais->sum() }}</div>
            </div>
        </div>
        @foreach($nichos as $key => $label)
        <div class="col-md-3">
            <a href="{{ route('super.admin.crm.leads', ['nicho' => $key]) }}" class="text-decoration-none">
                <div class="stat-chip {{ request('nicho') === $key ? 'border-primary' : '' }}">
                    <div class="lbl">{{ $label }}</div>
                    <div class="num">{{ $totais[$key] ?? 0 }}</div>
                </div>
            </a>
        </div>
        @endforeach
    </div>

    {{-- Filtros --}}
    <form method="GET" class="card border-0 shadow-sm p-3 mb-4">
        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <input type="text" name="busca" class="form-control form-control-sm"
                       placeholder="Nome, e-mail ou telefone..." value="{{ request('busca') }}">
            </div>
            <div class="col-md-3">
                <select name="nicho" class="form-select form-select-sm">
                    <option value="">Todos os nichos</option>
                    @foreach($nichos as $key => $label)
                        <option value="{{ $key }}" @selected(request('nicho') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="pipeline_status" class="form-select form-select-sm">
                    <option value="">Todos os estágios</option>
                    @foreach(\App\Models\Lead::$pipelineStatus as $val => $lbl)
                        <option value="{{ $val }}" @selected(request('pipeline_status') === $val)>{{ $lbl }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm w-100"><i class="fas fa-search me-1"></i>Filtrar</button>
                <a href="{{ route('super.admin.crm.leads') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-times"></i></a>
            </div>
        </div>
    </form>

    {{-- Tabela --}}
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Lead</th>
                        <th>Nicho</th>
                        <th>Telefone</th>
                        <th>Estágio</th>
                        <th>Temperatura</th>
                        <th>Cadastrado</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leads as $lead)
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $lead->nome }}</div>
                            <small class="text-muted">{{ $lead->email }}</small>
                        </td>
                        <td>
                            <span class="nicho-badge nicho-{{ $lead->origem }}">
                                @if($lead->origem === 'surf') 🏄
                                @elseif($lead->origem === 'pilates') 🧘
                                @else 🌐 @endif
                                {{ ucfirst($lead->origem) }}
                            </span>
                        </td>
                        <td>
                            @if($lead->telefone)
                                <a href="{{ $lead->whatsapp_url }}" target="_blank" class="text-success text-decoration-none">
                                    <i class="fab fa-whatsapp me-1"></i>{{ $lead->telefone }}
                                </a>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            <span class="pipeline-badge pipeline-{{ $lead->pipeline_status }}">
                                {{ $lead->pipeline_status_label }}
                            </span>
                        </td>
                        <td>
                            @php $temp = $lead->temperatura; @endphp
                            <span class="fw-semibold temp-{{ $temp }}">
                                @if($temp === 'quente') 🔥
                                @elseif($temp === 'morno') 🌡️
                                @else ❄️ @endif
                                {{ ucfirst($temp) }}
                            </span>
                        </td>
                        <td><small class="text-muted">{{ $lead->created_at->format('d/m/Y H:i') }}</small></td>
                        <td>
                            {{-- Mover estágio --}}
                            <form method="POST" action="{{ route('super.admin.crm.mover', $lead) }}" class="d-flex gap-1">
                                @csrf @method('PATCH')
                                <select name="pipeline_status" class="form-select form-select-sm" style="font-size:.75rem;width:130px">
                                    @foreach(\App\Models\Lead::$pipelineStatus as $val => $lbl)
                                        <option value="{{ $val }}" @selected($lead->pipeline_status === $val)>{{ $lbl }}</option>
                                    @endforeach
                                </select>
                                <button class="btn btn-sm btn-outline-primary" title="Mover">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-5">Nenhum lead encontrado.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($leads->hasPages())
        <div class="card-footer bg-white border-top-0 py-3 px-3">
            {{ $leads->links() }}
        </div>
        @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
