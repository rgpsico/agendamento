<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin — Nichos</title>
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
        .sidebar .nav-section { padding: 16px 24px 4px; font-size: .68rem; text-transform: uppercase; letter-spacing: .1em; color: #4a5568; }
        .main { margin-left: 240px; padding: 32px; }
        .nicho-card { background: #fff; border-radius: 14px; border: 1px solid #e9ecf0; overflow: hidden; transition: box-shadow .2s; }
        .nicho-card:hover { box-shadow: 0 6px 24px rgba(0,0,0,.08); }
        .nicho-card .cor-bar { height: 6px; }
        .nicho-card .body { padding: 20px; }
        .nicho-card .emoji { font-size: 2.2rem; }
        .nicho-card .nome { font-weight: 700; font-size: 1rem; color: #1a1f36; margin-top: 8px; }
        .nicho-card .dominio { font-size: .78rem; color: #8892b0; margin-top: 2px; }
        .nicho-card .preview-img { width: 100%; height: 90px; object-fit: cover; border-radius: 8px; margin-top: 12px; background: #f4f6fb; }
        .nicho-card .actions { display: flex; gap: 8px; margin-top: 14px; }
        .badge-on  { background: #d1fae5; color: #065f46; }
        .badge-off { background: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="logo"><i class="fas fa-layer-group me-2"></i> Super Admin<small>{{ auth()->user()->email }}</small></div>
    <nav class="mt-2">
        <a href="{{ route('super.admin.index') }}"    class="nav-link"><i class="fas fa-chart-pie"></i> Dashboard</a>
        <a href="{{ route('super.admin.clientes') }}" class="nav-link"><i class="fas fa-building"></i> Clientes</a>
        <a href="{{ route('super.admin.nichos') }}"   class="nav-link active"><i class="fas fa-palette"></i> Nichos</a>
        <a href="{{ route('super.admin.videos') }}"   class="nav-link"><i class="fas fa-play-circle"></i> Vídeos</a>
        <div class="nav-section">CRM</div>
        <a href="{{ route('super.admin.crm.leads') }}"    class="nav-link"><i class="fas fa-users"></i> Leads</a>
        <a href="{{ route('super.admin.crm.pipeline') }}" class="nav-link"><i class="fas fa-columns"></i> Pipeline</a>
        <a href="{{ route('home') }}" class="nav-link mt-3"><i class="fas fa-arrow-left"></i> Voltar</a>
    </nav>
</div>

<div class="main">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0 fw-bold">Configuração de Nichos</h4>
            <small class="text-muted">Cores, imagens e domínios por nicho</small>
        </div>
        <a href="{{ route('super.admin.nichos.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> Novo nicho
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success py-2">{{ session('success') }}</div>
    @endif

    <div class="row g-3">
        @forelse($nichos as $n)
        <div class="col-md-4">
            <div class="nicho-card">
                <div class="cor-bar" style="background: linear-gradient(90deg, {{ $n->cor_primaria }}, {{ $n->cor_secundaria }})"></div>
                <div class="body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="emoji">{{ $n->emoji ?? '🏢' }}</div>
                        <span class="badge rounded-pill px-3 {{ $n->ativo ? 'badge-on' : 'badge-off' }}">
                            {{ $n->ativo ? 'Ativo' : 'Inativo' }}
                        </span>
                    </div>
                    <div class="nome">{{ $n->nome }}</div>
                    <div class="dominio">
                        @if($n->dominio)
                            <i class="fas fa-globe me-1"></i>{{ $n->dominio }}
                        @else
                            <span class="text-muted">Sem domínio</span>
                        @endif
                    </div>

                    {{-- Cores --}}
                    <div class="d-flex gap-2 mt-3 align-items-center">
                        <div style="width:28px;height:28px;border-radius:50%;background:{{ $n->cor_primaria }};border:2px solid #e9ecf0"
                             title="{{ $n->cor_primaria }}"></div>
                        <div style="width:28px;height:28px;border-radius:50%;background:{{ $n->cor_secundaria }};border:2px solid #e9ecf0"
                             title="{{ $n->cor_secundaria }}"></div>
                        <small class="text-muted ms-1">{{ $n->cor_primaria }} · {{ $n->cor_secundaria }}</small>
                    </div>

                    {{-- Preview imagem de login --}}
                    @if($n->login_imagem)
                        <img src="{{ $n->login_imagem_url }}" alt="Login" class="preview-img">
                        <small class="text-muted d-block mt-1">Imagem de login</small>
                    @endif

                    <div class="actions">
                        <a href="{{ route('super.admin.nichos.edit', $n) }}" class="btn btn-sm btn-outline-primary flex-fill">
                            <i class="fas fa-edit me-1"></i> Editar
                        </a>
                        <form method="POST" action="{{ route('super.admin.nichos.destroy', $n) }}"
                              onsubmit="return confirm('Remover este nicho?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center text-muted py-5">
            Nenhum nicho cadastrado. <a href="{{ route('super.admin.nichos.create') }}">Criar o primeiro</a>.
        </div>
        @endforelse
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
