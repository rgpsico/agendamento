<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin — Conteúdo & Artigos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f4f6fb; font-family: 'Segoe UI', sans-serif; }
        .sidebar { width: 240px; min-height: 100vh; background: #1a1f36; position: fixed; top: 0; left: 0; }
        .sidebar .logo { padding: 28px 24px 16px; color: #fff; font-size: 1.1rem; font-weight: 700; border-bottom: 1px solid #2d3354; }
        .sidebar .logo small { display: block; font-size: .7rem; color: #8892b0; margin-top: 2px; }
        .sidebar .nav-link { color: #8892b0; padding: 10px 24px; font-size: .88rem; display: flex; align-items: center; gap: 10px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background: #2d3354; }
        .sidebar .nav-link i { width: 16px; }
        .sidebar .nav-section { padding: 16px 24px 4px; font-size: .68rem; text-transform: uppercase; letter-spacing: .1em; color: #4a5568; }
        .main { margin-left: 240px; padding: 32px; }

        /* Cards */
        .content-card { background: #fff; border-radius: 14px; border: 1px solid #e9ecf0; overflow: hidden; transition: box-shadow .2s, transform .2s; height: 100%; display: flex; flex-direction: column; }
        .content-card:hover { box-shadow: 0 8px 28px rgba(0,0,0,.1); transform: translateY(-2px); }
        .content-card .card-capa { height: 140px; object-fit: cover; width: 100%; }
        .content-card .card-placeholder { height: 140px; display: flex; align-items: center; justify-content: center; }
        .content-card .card-body { padding: 14px 16px; flex: 1; }
        .content-card .card-actions { padding: 10px 16px; border-top: 1px solid #f1f5f9; display: flex; gap: 6px; align-items: center; flex-wrap: wrap; }
        .content-card .titulo { font-weight: 700; font-size: .93rem; color: #1a1f36; line-height: 1.3; margin-bottom: 6px; }
        .content-card .legenda-preview { font-size: .8rem; color: #64748b; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .badge-formato { font-size: .68rem; font-weight: 600; }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="logo"><i class="fas fa-layer-group me-2"></i> Super Admin<small>{{ auth()->user()->email }}</small></div>
    <nav class="mt-2">
        <a href="{{ route('super.admin.index') }}"     class="nav-link"><i class="fas fa-chart-pie"></i> Dashboard</a>
        <a href="{{ route('super.admin.clientes') }}"  class="nav-link"><i class="fas fa-building"></i> Clientes</a>
        <a href="{{ route('super.admin.nichos') }}"    class="nav-link"><i class="fas fa-palette"></i> Nichos</a>
        <a href="{{ route('super.admin.videos') }}"    class="nav-link"><i class="fas fa-play-circle"></i> Vídeos</a>
        <a href="{{ route('super.admin.conteudos') }}" class="nav-link active"><i class="fas fa-pen-nib"></i> Conteúdo / IA</a>
        <div class="nav-section">CRM</div>
        <a href="{{ route('super.admin.crm.leads') }}"      class="nav-link"><i class="fas fa-users"></i> Leads</a>
        <a href="{{ route('super.admin.crm.pipeline') }}"   class="nav-link"><i class="fas fa-columns"></i> Pipeline</a>
        <a href="{{ route('super.admin.crm.sequencias') }}" class="nav-link"><i class="fas fa-robot"></i> Sequências</a>
        <a href="{{ route('super.admin.crm.templates') }}"  class="nav-link"><i class="fas fa-envelope-open-text"></i> Templates</a>
        <a href="{{ route('home') }}" class="nav-link mt-3"><i class="fas fa-arrow-left"></i> Voltar ao sistema</a>
    </nav>
</div>

<div class="main">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0 fw-bold"><i class="fas fa-pen-nib text-primary me-2"></i>Conteúdo & Artigos</h4>
            <small class="text-muted">Gere artigos, posts e roteiros com IA para divulgar o sistema</small>
        </div>
        <a href="{{ route('super.admin.conteudos.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-magic me-1"></i> Gerar Novo Conteúdo
        </a>
    </div>

    {{-- Alertas --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-1"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Filtros --}}
    <div class="card border-0 shadow-sm mb-4" style="border-radius:12px;">
        <div class="card-body py-2">
            <form method="GET" class="d-flex flex-wrap gap-2 align-items-center">
                <select name="nicho" class="form-select form-select-sm" style="width:auto;">
                    <option value="">Todos os nichos</option>
                    @foreach($nichos as $n)
                        <option value="{{ $n->nicho }}" @selected($nicho === $n->nicho)>{{ $n->nome }}</option>
                    @endforeach
                </select>
                <select name="formato" class="form-select form-select-sm" style="width:auto;">
                    <option value="">Todos os formatos</option>
                    <option value="artigo"         @selected($formato === 'artigo')>📰 Artigo</option>
                    <option value="post_instagram" @selected($formato === 'post_instagram')>📸 Instagram</option>
                    <option value="post_tiktok"    @selected($formato === 'post_tiktok')>🎵 TikTok</option>
                    <option value="legenda_video"  @selected($formato === 'legenda_video')>🎬 Legenda Vídeo</option>
                </select>
                <select name="status" class="form-select form-select-sm" style="width:auto;">
                    <option value="">Todos os status</option>
                    <option value="rascunho"  @selected($status === 'rascunho')>Rascunho</option>
                    <option value="revisado"  @selected($status === 'revisado')>Revisado</option>
                    <option value="publicado" @selected($status === 'publicado')>Publicado</option>
                </select>
                <button class="btn btn-sm btn-secondary"><i class="fas fa-filter me-1"></i>Filtrar</button>
                <a href="{{ route('super.admin.conteudos') }}" class="btn btn-sm btn-outline-secondary">Limpar</a>
                <span class="ms-auto text-muted small">{{ $conteudos->total() }} conteúdo(s)</span>
            </form>
        </div>
    </div>

    {{-- Grid de cards --}}
    @if($conteudos->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="fas fa-pen-nib fa-3x mb-3 d-block opacity-25"></i>
            <p class="mb-1 fw-semibold">Nenhum conteúdo encontrado</p>
            <a href="{{ route('super.admin.conteudos.create') }}" class="btn btn-primary btn-sm mt-2">
                <i class="fas fa-magic me-1"></i> Gerar o primeiro agora
            </a>
        </div>
    @else
        <div class="row g-3">
            @foreach($conteudos as $item)
            @php
                $fmtColors = ['artigo'=>'primary','post_instagram'=>'danger','post_tiktok'=>'dark','legenda_video'=>'warning'];
                $fmtIcons  = ['artigo'=>'newspaper','post_instagram'=>'square','post_tiktok'=>'video','legenda_video'=>'film'];
                $fmtLabels = ['artigo'=>'Artigo','post_instagram'=>'Instagram','post_tiktok'=>'TikTok','legenda_video'=>'Legenda Vídeo'];
                $stColors  = ['rascunho'=>'secondary','revisado'=>'warning','publicado'=>'success'];
                $stLabels  = ['rascunho'=>'Rascunho','revisado'=>'Revisado','publicado'=>'Publicado'];
                $cor = $fmtColors[$item->formato] ?? 'secondary';
            @endphp
            <div class="col-xl-4 col-lg-6">
                <div class="content-card">
                    {{-- Capa --}}
                    @if($item->imagem_capa)
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($item->imagem_capa) }}"
                             class="card-capa" alt="">
                    @else
                        <div class="card-placeholder bg-{{ $cor }} bg-opacity-10">
                            <i class="fas fa-{{ $fmtIcons[$item->formato] ?? 'align-left' }} fa-3x text-{{ $cor }} opacity-25"></i>
                        </div>
                    @endif

                    <div class="card-body">
                        <div class="d-flex gap-1 flex-wrap mb-2">
                            <span class="badge bg-{{ $cor }} bg-opacity-10 text-{{ $cor }} badge-formato">
                                {{ $fmtLabels[$item->formato] ?? $item->formato }}
                            </span>
                            <span class="badge bg-{{ $stColors[$item->status] ?? 'secondary' }} bg-opacity-10 text-{{ $stColors[$item->status] ?? 'secondary' }} badge-formato">
                                {{ $stLabels[$item->status] ?? $item->status }}
                            </span>
                            @if($item->nicho)
                                <span class="badge bg-light text-dark border badge-formato">{{ $item->nicho }}</span>
                            @endif
                        </div>
                        <div class="titulo">{{ Str::limit($item->titulo, 70) }}</div>
                        @if($item->legenda)
                            <div class="legenda-preview mt-1">{{ $item->legenda }}</div>
                        @endif
                        @if($item->palavras_count)
                            <div class="mt-2"><small class="text-muted"><i class="fas fa-align-left me-1"></i>{{ number_format($item->palavras_count) }} palavras</small></div>
                        @endif
                    </div>

                    <div class="card-actions">
                        <small class="text-muted me-auto">{{ $item->created_at->format('d/m/Y') }}</small>
                        <a href="{{ route('super.admin.conteudos.edit', $item) }}"
                           class="btn btn-sm btn-outline-primary" title="Editar">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button class="btn btn-sm btn-outline-success btn-copiar"
                                data-corpo="{{ htmlspecialchars(strip_tags($item->corpo ?? ''), ENT_QUOTES) }}"
                                title="Copiar texto">
                            <i class="fas fa-copy"></i>
                        </button>
                        @if(in_array($item->formato, ['post_instagram','artigo']))
                        <a href="https://www.instagram.com/" target="_blank"
                           class="btn btn-sm btn-outline-danger" title="Abrir Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        @endif
                        @if($item->formato === 'post_tiktok')
                        <a href="https://www.tiktok.com/" target="_blank"
                           class="btn btn-sm btn-outline-dark" title="Abrir TikTok">
                            <i class="fab fa-tiktok"></i>
                        </a>
                        @endif
                        <form method="POST" action="{{ route('super.admin.conteudos.destroy', $item) }}"
                              class="d-inline" onsubmit="return confirm('Remover este conteúdo?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-secondary" title="Remover">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>

                    @if($item->publicado_instagram_em || $item->publicado_tiktok_em)
                    <div class="px-3 py-2 border-top bg-light">
                        <small class="text-muted">
                            @if($item->publicado_instagram_em)
                                <i class="fab fa-instagram text-danger me-1"></i>{{ \Carbon\Carbon::parse($item->publicado_instagram_em)->format('d/m H:i') }}
                            @endif
                            @if($item->publicado_tiktok_em)
                                <i class="fab fa-tiktok ms-2 me-1"></i>{{ \Carbon\Carbon::parse($item->publicado_tiktok_em)->format('d/m H:i') }}
                            @endif
                        </small>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-4">{{ $conteudos->withQueryString()->links() }}</div>
    @endif

</div>{{-- /main --}}

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.querySelectorAll('.btn-copiar').forEach(btn => {
    btn.addEventListener('click', function () {
        navigator.clipboard.writeText(this.dataset.corpo).then(() => {
            const orig = this.innerHTML;
            this.innerHTML = '<i class="fas fa-check"></i>';
            this.classList.replace('btn-outline-success', 'btn-success');
            setTimeout(() => {
                this.innerHTML = orig;
                this.classList.replace('btn-success', 'btn-outline-success');
            }, 1800);
        });
    });
});
</script>
</body>
</html>
