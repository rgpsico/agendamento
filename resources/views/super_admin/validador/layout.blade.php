<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin — @yield('titulo', 'Validador')</title>
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
        .sidebar-toggle-btn { display: none; position: fixed; top: 14px; left: 16px; z-index: 1051; width: 42px; height: 42px; border-radius: 10px; background: #1a1f36; color: #fff; border: none; align-items: center; justify-content: center; font-size: 1.1rem; }
        .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.4); z-index: 1049; }
        @media (max-width: 991px) {
            .sidebar-toggle-btn { display: flex; }
            .sidebar { transform: translateX(-100%); transition: transform 0.25s ease; z-index: 1050; }
            .sidebar.open { transform: translateX(0); }
            .sidebar-overlay.show { display: block; }
            .main { margin-left: 0; padding: 76px 16px 20px; }
        }
        .stat-chip { background: #fff; border: 1px solid #e9ecf0; border-radius: 10px; padding: 14px 20px; }
        .stat-chip .num { font-size: 1.6rem; font-weight: 800; color: #1a1f36; }
        .stat-chip .lbl { font-size: 0.75rem; color: #8892b0; text-transform: uppercase; letter-spacing: .05em; }
        .pill { display: inline-block; padding: 3px 10px; border-radius: 100px; font-size: .75rem; font-weight: 600; }
        .pill-basico { background: #fee2e2; color: #991b1b; }
        .pill-intermediario { background: #fef3c7; color: #92400e; }
        .pill-avancado { background: #d1fae5; color: #065f46; }
        .pill-pendente { background: #e5e7eb; color: #374151; }
        .pill-respondido { background: #dbeafe; color: #1e40af; }
        .pill-trial { background: #fef3c7; color: #92400e; }
        .pill-cliente { background: #d1fae5; color: #065f46; }
        .leitura-ok { color: #047857; } .leitura-neutro { color: #92400e; } .leitura-alerta { color: #b91c1c; }
    </style>
</head>
<body>
<button class="sidebar-toggle-btn" type="button" onclick="document.querySelector('.sidebar').classList.toggle('open'); document.querySelector('.sidebar-overlay').classList.toggle('show');"><i class="fas fa-bars"></i></button>
<div class="sidebar-overlay" onclick="document.querySelector('.sidebar').classList.remove('open'); this.classList.remove('show');"></div>
<div class="sidebar">
    <div class="logo">
        <i class="fas fa-layer-group me-2"></i> Super Admin
        <small>{{ auth()->user()->email }}</small>
    </div>
    <nav class="mt-2">
        <a href="{{ route('super.admin.index') }}"    class="nav-link"><i class="fas fa-chart-pie"></i> Dashboard</a>
        <a href="{{ route('super.admin.clientes') }}" class="nav-link"><i class="fas fa-building"></i> Clientes</a>
        <a href="{{ route('super.admin.nichos') }}"   class="nav-link"><i class="fas fa-palette"></i> Nichos</a>
        <a href="{{ route('super.admin.videos') }}"   class="nav-link"><i class="fas fa-play-circle"></i> Vídeos</a>
        <a href="{{ route('super.admin.conteudos') }}" class="nav-link"><i class="fas fa-pen-nib"></i> Conteúdo / IA</a>
        <div class="nav-section">CRM</div>
        <a href="{{ route('super.admin.crm.leads') }}"      class="nav-link"><i class="fas fa-users"></i> Leads</a>
        <a href="{{ route('super.admin.crm.pipeline') }}"   class="nav-link"><i class="fas fa-columns"></i> Pipeline</a>
        <a href="{{ route('super.admin.validador.index') }}" class="nav-link active"><i class="fas fa-clipboard-check"></i> Validador</a>
        <a href="{{ route('super.admin.crm.sequencias') }}" class="nav-link"><i class="fas fa-robot"></i> Sequências</a>
        <a href="{{ route('super.admin.crm.templates') }}"  class="nav-link"><i class="fas fa-envelope-open-text"></i> Templates</a>
        <a href="{{ route('home') }}" class="nav-link mt-3"><i class="fas fa-arrow-left"></i> Voltar</a>
    </nav>
</div>

<div class="main">
    @if(session('success'))
        <div class="alert alert-success py-2">{{ session('success') }}</div>
    @endif
    @yield('content')
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
