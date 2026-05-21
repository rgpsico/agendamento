<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin — Vídeos do Sistema</title>
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

        /* Cards de vídeo */
        .video-card { background: #fff; border-radius: 14px; border: 1px solid #e9ecf0; overflow: hidden; transition: box-shadow .2s, transform .2s; }
        .video-card:hover { box-shadow: 0 8px 28px rgba(0,0,0,.1); transform: translateY(-2px); }
        .video-card .thumb-wrap { position: relative; aspect-ratio: 16/9; overflow: hidden; background: #0f172a; cursor: pointer; }
        .video-card .thumb-wrap img { width: 100%; height: 100%; object-fit: cover; transition: opacity .2s; }
        .video-card .thumb-wrap:hover img { opacity: .85; }
        .play-btn { position: absolute; top: 50%; left: 50%; transform: translate(-50%,-50%);
            width: 52px; height: 52px; background: rgba(255,255,255,.9); border-radius: 50%;
            display: flex; align-items: center; justify-content: center; font-size: 1.2rem; color: #1a1f36;
            transition: transform .2s; pointer-events: none; }
        .thumb-wrap:hover .play-btn { transform: translate(-50%,-50%) scale(1.1); }
        .duracao-badge { position: absolute; bottom: 8px; right: 8px; background: rgba(0,0,0,.75);
            color: #fff; font-size: .7rem; padding: 2px 6px; border-radius: 4px; }
        .nicho-pill { font-size: .7rem; padding: 2px 8px; border-radius: 20px; font-weight: 600; }
        .tipo-badge { font-size: .68rem; padding: 2px 7px; border-radius: 4px; font-weight: 600; }
        .tipo-youtube  { background: #fee2e2; color: #991b1b; }
        .tipo-vimeo    { background: #ede9fe; color: #5b21b6; }
        .tipo-upload   { background: #dbeafe; color: #1e40af; }
        .card-body-video { padding: 14px 16px; }
        .card-body-video .titulo { font-weight: 700; font-size: .95rem; color: #1a1f36; line-height: 1.3; }
        .card-body-video .desc { font-size: .8rem; color: #64748b; margin-top: 4px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .card-actions { padding: 10px 16px; border-top: 1px solid #f1f5f9; display: flex; gap: 6px; align-items: center; }
        .card-inativo { opacity: .55; }

        /* Modal player */
        .player-wrap { position: relative; aspect-ratio: 16/9; background: #000; border-radius: 8px; overflow: hidden; }
        .player-wrap iframe, .player-wrap video { width: 100%; height: 100%; border: none; }

        /* Drag reorder visual */
        .video-card.dragging { opacity: .4; transform: scale(.97); }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="logo"><i class="fas fa-layer-group me-2"></i> Super Admin<small>{{ auth()->user()->email }}</small></div>
    <nav class="mt-2">
        <a href="{{ route('super.admin.index') }}"    class="nav-link"><i class="fas fa-chart-pie"></i> Dashboard</a>
        <a href="{{ route('super.admin.clientes') }}" class="nav-link"><i class="fas fa-building"></i> Clientes</a>
        <a href="{{ route('super.admin.nichos') }}"   class="nav-link"><i class="fas fa-palette"></i> Nichos</a>
        <a href="{{ route('super.admin.videos') }}"   class="nav-link active"><i class="fas fa-play-circle"></i> Vídeos</a>
        <div class="nav-section">CRM</div>
        <a href="{{ route('super.admin.crm.leads') }}"      class="nav-link"><i class="fas fa-users"></i> Leads</a>
        <a href="{{ route('super.admin.crm.pipeline') }}"   class="nav-link"><i class="fas fa-columns"></i> Pipeline</a>
        <a href="{{ route('super.admin.crm.sequencias') }}" class="nav-link"><i class="fas fa-robot"></i> Sequências</a>
        <a href="{{ route('super.admin.crm.templates') }}"  class="nav-link"><i class="fas fa-envelope-open-text"></i> Templates</a>
        <a href="{{ route('home') }}" class="nav-link mt-3"><i class="fas fa-arrow-left"></i> Voltar</a>
    </nav>
</div>

<div class="main">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0 fw-bold">Vídeos do Sistema</h4>
            <small class="text-muted">Gerencie demos e materiais para enviar a leads</small>
        </div>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalNovoVideo">
            <i class="fas fa-plus me-1"></i> Novo vídeo
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success py-2 mb-3">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger py-2 mb-3">
            <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    {{-- Filtros --}}
    <form method="GET" class="d-flex gap-2 mb-4 flex-wrap align-items-center">
        <select name="nicho" class="form-select form-select-sm" style="width:180px" onchange="this.form.submit()">
            <option value="">Todos os nichos</option>
            @foreach($nichos as $n)
                <option value="{{ $n->nicho }}" @selected($nicho === $n->nicho)>
                    {{ $n->emoji ?? '' }} {{ $n->nome }}
                </option>
            @endforeach
        </select>

        @if($categorias->isNotEmpty())
        <select name="categoria" class="form-select form-select-sm" style="width:180px" onchange="this.form.submit()">
            <option value="">Todas as categorias</option>
            @foreach($categorias as $cat)
                <option value="{{ $cat }}" @selected($categoria === $cat)>{{ $cat }}</option>
            @endforeach
        </select>
        @endif

        @if($nicho || $categoria)
        <a href="{{ route('super.admin.videos') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-times me-1"></i> Limpar
        </a>
        @endif

        <span class="text-muted ms-auto" style="font-size:.83rem">{{ $videos->count() }} vídeo(s)</span>
    </form>

    {{-- Grid de vídeos --}}
    @if($videos->isEmpty())
    <div class="text-center py-5 text-muted">
        <i class="fas fa-film fa-3x mb-3 d-block opacity-25"></i>
        <div>Nenhum vídeo cadastrado.</div>
        <button class="btn btn-primary btn-sm mt-3" data-bs-toggle="modal" data-bs-target="#modalNovoVideo">
            Cadastrar o primeiro
        </button>
    </div>
    @else
    <div class="row g-3" id="videoGrid">
        @foreach($videos as $video)
        <div class="col-md-4 col-lg-3" id="card_{{ $video->id }}">
            <div class="video-card {{ !$video->ativo ? 'card-inativo' : '' }}">

                {{-- Thumbnail clicável --}}
                <div class="thumb-wrap" onclick="abrirPlayer({{ $video->id }}, '{{ addslashes($video->titulo) }}', '{{ $video->tipo }}', '{{ $video->embed_url }}')">
                    <img src="{{ $video->thumbnail_url }}"
                         alt="{{ $video->titulo }}"
                         onerror="this.src='https://via.placeholder.com/400x225/0f172a/475569?text=Vídeo'">
                    <div class="play-btn"><i class="fas fa-play ms-1"></i></div>
                    @if($video->duracao_formatada)
                        <span class="duracao-badge">{{ $video->duracao_formatada }}</span>
                    @endif
                    @if(!$video->ativo)
                        <span style="position:absolute;top:8px;left:8px;background:rgba(0,0,0,.7);color:#fca5a5;font-size:.68rem;padding:2px 8px;border-radius:4px">
                            <i class="fas fa-eye-slash me-1"></i>Inativo
                        </span>
                    @endif
                </div>

                <div class="card-body-video">
                    <div class="d-flex gap-1 mb-2 flex-wrap">
                        <span class="tipo-badge tipo-{{ $video->tipo }}">
                            @if($video->tipo === 'youtube') <i class="fab fa-youtube me-1"></i>YouTube
                            @elseif($video->tipo === 'vimeo') <i class="fab fa-vimeo me-1"></i>Vimeo
                            @else <i class="fas fa-upload me-1"></i>Upload @endif
                        </span>
                        @if($video->nicho)
                            <span class="nicho-pill bg-primary bg-opacity-10 text-primary">{{ $video->nicho }}</span>
                        @endif
                        @if($video->categoria)
                            <span class="nicho-pill bg-secondary bg-opacity-10 text-secondary">{{ $video->categoria }}</span>
                        @endif
                    </div>
                    <div class="titulo">{{ $video->titulo }}</div>
                    @if($video->descricao)
                        <div class="desc">{{ $video->descricao }}</div>
                    @endif
                </div>

                <div class="card-actions">
                    {{-- Copiar link --}}
                    <button class="btn btn-sm btn-outline-secondary flex-fill"
                            onclick="copiarLink('{{ $video->link_compartilhar }}', this)"
                            title="Copiar link">
                        <i class="fas fa-link me-1"></i> Copiar
                    </button>

                    {{-- WhatsApp --}}
                    @php
                        $msgWa = urlencode("Olá! Preparei um vídeo demonstrando o sistema: {$video->titulo}\n{$video->link_compartilhar}");
                    @endphp
                    <a href="https://wa.me/?text={{ $msgWa }}" target="_blank"
                       class="btn btn-sm btn-outline-success" title="Enviar via WhatsApp">
                        <i class="fab fa-whatsapp"></i>
                    </a>

                    {{-- Editar --}}
                    <button class="btn btn-sm btn-outline-primary"
                            onclick="abrirEditar({{ $video->id }}, '{{ addslashes($video->titulo) }}', '{{ addslashes($video->descricao ?? '') }}', '{{ $video->tipo }}', '{{ $video->url }}', '{{ addslashes($video->nicho ?? '') }}', '{{ addslashes($video->categoria ?? '') }}', {{ $video->duracao_segundos ?? 'null' }}, {{ $video->ordem }}, {{ $video->ativo ? 'true' : 'false' }})"
                            title="Editar">
                        <i class="fas fa-edit"></i>
                    </button>

                    {{-- Toggle ativo --}}
                    <form method="POST" action="{{ route('super.admin.videos.toggle', $video) }}">
                        @csrf @method('PATCH')
                        <button class="btn btn-sm {{ $video->ativo ? 'btn-outline-warning' : 'btn-outline-secondary' }}"
                                title="{{ $video->ativo ? 'Desativar' : 'Ativar' }}">
                            <i class="fas fa-{{ $video->ativo ? 'eye-slash' : 'eye' }}"></i>
                        </button>
                    </form>

                    {{-- Excluir --}}
                    <form method="POST" action="{{ route('super.admin.videos.destroy', $video) }}"
                          onsubmit="return confirm('Remover o vídeo \'{{ $video->titulo }}\'?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger" title="Excluir">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

{{-- ─── Modal Novo Vídeo ─────────────────────────────────────────────── --}}
<div class="modal fade" id="modalNovoVideo" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="{{ route('super.admin.videos.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-play-circle me-2 text-primary"></i>Novo Vídeo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">

                        {{-- Tipo --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">Tipo de vídeo <span class="text-danger">*</span></label>
                            <div class="d-flex gap-3">
                                <label class="d-flex align-items-center gap-2 cursor-pointer">
                                    <input type="radio" name="tipo" value="youtube" checked onchange="trocarTipo(this.value)">
                                    <span class="tipo-badge tipo-youtube px-3 py-2"><i class="fab fa-youtube me-1"></i>YouTube</span>
                                </label>
                                <label class="d-flex align-items-center gap-2 cursor-pointer">
                                    <input type="radio" name="tipo" value="vimeo" onchange="trocarTipo(this.value)">
                                    <span class="tipo-badge tipo-vimeo px-3 py-2"><i class="fab fa-vimeo me-1"></i>Vimeo</span>
                                </label>
                                <label class="d-flex align-items-center gap-2 cursor-pointer">
                                    <input type="radio" name="tipo" value="upload" onchange="trocarTipo(this.value)">
                                    <span class="tipo-badge tipo-upload px-3 py-2"><i class="fas fa-upload me-1"></i>Upload</span>
                                </label>
                            </div>
                        </div>

                        {{-- URL (YouTube/Vimeo) --}}
                        <div class="col-12" id="campoUrl">
                            <label class="form-label fw-semibold">URL do vídeo <span class="text-danger">*</span></label>
                            <input type="url" name="url" id="urlInput" class="form-control"
                                   placeholder="https://www.youtube.com/watch?v=..."
                                   oninput="previewThumb(this.value)">
                            <small class="text-muted">Cole o link do YouTube ou Vimeo</small>
                        </div>

                        {{-- Preview thumb auto --}}
                        <div class="col-12" id="thumbPreviewWrap" style="display:none">
                            <div class="position-relative d-inline-block">
                                <img id="thumbPreview" src="" alt="Preview" style="height:100px;border-radius:8px;border:2px solid #e2e8f0">
                                <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);background:rgba(0,0,0,.6);width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff">
                                    <i class="fas fa-play ms-1" style="font-size:.75rem"></i>
                                </div>
                            </div>
                            <small class="text-muted d-block mt-1">Preview da thumbnail</small>
                        </div>

                        {{-- Upload de arquivo --}}
                        <div class="col-12" id="campoArquivo" style="display:none">
                            <label class="form-label fw-semibold">Arquivo de vídeo <span class="text-danger">*</span></label>
                            <input type="file" name="arquivo" class="form-control" accept="video/mp4,video/webm,video/ogg">
                            <small class="text-muted">MP4, WebM ou OGG. Máx: 200MB</small>
                        </div>

                        {{-- Título --}}
                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Título <span class="text-danger">*</span></label>
                            <input type="text" name="titulo" class="form-control" placeholder="Ex: Tour pelo painel do Surf Gestão" required>
                        </div>

                        {{-- Duração --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Duração</label>
                            <div class="d-flex gap-2 align-items-center">
                                <input type="number" id="durMin" class="form-control" placeholder="min" min="0" max="999" oninput="calcDuracao()">
                                <span class="text-muted">:</span>
                                <input type="number" id="durSeg" class="form-control" placeholder="seg" min="0" max="59" oninput="calcDuracao()">
                                <input type="hidden" name="duracao_segundos" id="duracaoSegundos">
                            </div>
                        </div>

                        {{-- Descrição --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">Descrição</label>
                            <textarea name="descricao" class="form-control" rows="2"
                                placeholder="Breve descrição do que o vídeo mostra..."></textarea>
                        </div>

                        {{-- Nicho / Categoria / Ordem --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Nicho</label>
                            <select name="nicho" class="form-select">
                                <option value="">Todos os nichos</option>
                                @foreach($nichos as $n)
                                <option value="{{ $n->nicho }}">{{ $n->emoji ?? '' }} {{ $n->nome }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Categoria</label>
                            <input type="text" name="categoria" class="form-control"
                                   placeholder="Ex: Funcionalidades, Demo, Depoimento"
                                   list="categoriasExistentes">
                            <datalist id="categoriasExistentes">
                                @foreach($categorias as $cat)
                                <option value="{{ $cat }}">
                                @endforeach
                                <option value="Demo do sistema">
                                <option value="Funcionalidades">
                                <option value="Depoimentos">
                                <option value="Tutorial">
                            </datalist>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Ordem</label>
                            <input type="number" name="ordem" class="form-control" value="0" min="0">
                            <small class="text-muted">Menor número aparece primeiro</small>
                        </div>

                        {{-- Thumbnail customizada --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">Thumbnail customizada <span class="text-muted fw-normal">(opcional)</span></label>
                            <input type="file" name="thumbnail" class="form-control" accept="image/*">
                            <small class="text-muted">Para YouTube/Vimeo, se não enviar usa a do próprio vídeo</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Salvar vídeo</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- ─── Modal Editar Vídeo ───────────────────────────────────────────── --}}
<div class="modal fade" id="modalEditarVideo" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form method="POST" id="formEditar" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-edit me-2 text-primary"></i>Editar Vídeo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Título <span class="text-danger">*</span></label>
                            <input type="text" name="titulo" id="editTitulo" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">URL do vídeo</label>
                            <input type="url" name="url" id="editUrl" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Descrição</label>
                            <textarea name="descricao" id="editDescricao" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Nicho</label>
                            <select name="nicho" id="editNicho" class="form-select">
                                <option value="">Todos os nichos</option>
                                @foreach($nichos as $n)
                                <option value="{{ $n->nicho }}">{{ $n->emoji ?? '' }} {{ $n->nome }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Categoria</label>
                            <input type="text" name="categoria" id="editCategoria" class="form-control" list="categoriasExistentes">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Ordem</label>
                            <input type="number" name="ordem" id="editOrdem" class="form-control" min="0">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="ativo" id="editAtivo" value="1">
                                <label class="form-check-label" for="editAtivo">Ativo</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Nova thumbnail <span class="text-muted fw-normal">(opcional)</span></label>
                            <input type="file" name="thumbnail" class="form-control" accept="image/*">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Salvar alterações</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- ─── Modal Player ─────────────────────────────────────────────────── --}}
<div class="modal fade" id="modalPlayer" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content bg-black border-0">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title text-white" id="playerTitulo"></h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-2">
                <div class="player-wrap" id="playerWrap"></div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// ─── Trocar tipo (YouTube/Vimeo/Upload) ──────────────────────────────────────
function trocarTipo(tipo) {
    document.getElementById('campoUrl').style.display    = tipo !== 'upload' ? '' : 'none';
    document.getElementById('campoArquivo').style.display = tipo === 'upload' ? '' : 'none';
    if (tipo === 'upload') {
        document.getElementById('thumbPreviewWrap').style.display = 'none';
    }
}

// ─── Preview thumbnail ao digitar URL ────────────────────────────────────────
function extrairYoutubeId(url) {
    const m = url.match(/(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/);
    return m ? m[1] : null;
}
function extrairVimeoId(url) {
    const m = url.match(/vimeo\.com\/(\d+)/);
    return m ? m[1] : null;
}

function previewThumb(url) {
    const tipo = document.querySelector('input[name="tipo"]:checked')?.value;
    const wrap  = document.getElementById('thumbPreviewWrap');
    const img   = document.getElementById('thumbPreview');

    let thumbUrl = null;
    if (tipo === 'youtube') {
        const id = extrairYoutubeId(url);
        if (id) thumbUrl = `https://img.youtube.com/vi/${id}/hqdefault.jpg`;
    } else if (tipo === 'vimeo') {
        const id = extrairVimeoId(url);
        if (id) thumbUrl = `https://vumbnail.com/${id}.jpg`;
    }

    if (thumbUrl) {
        img.src = thumbUrl;
        wrap.style.display = '';
    } else {
        wrap.style.display = 'none';
    }
}

// ─── Calcular duração em segundos ────────────────────────────────────────────
function calcDuracao() {
    const m = parseInt(document.getElementById('durMin').value) || 0;
    const s = parseInt(document.getElementById('durSeg').value) || 0;
    document.getElementById('duracaoSegundos').value = m * 60 + s;
}

// ─── Modal Player ─────────────────────────────────────────────────────────────
function abrirPlayer(id, titulo, tipo, embedUrl) {
    document.getElementById('playerTitulo').textContent = titulo;
    const wrap = document.getElementById('playerWrap');
    wrap.innerHTML = '';

    if (tipo === 'upload') {
        wrap.innerHTML = `<video src="${embedUrl}" controls autoplay style="width:100%;height:100%;border-radius:8px"></video>`;
    } else {
        wrap.innerHTML = `<iframe src="${embedUrl}" allow="autoplay; fullscreen" allowfullscreen style="border-radius:8px"></iframe>`;
    }

    new bootstrap.Modal(document.getElementById('modalPlayer')).show();
}

// Parar vídeo ao fechar modal
document.getElementById('modalPlayer').addEventListener('hidden.bs.modal', function () {
    document.getElementById('playerWrap').innerHTML = '';
});

// ─── Modal Editar ─────────────────────────────────────────────────────────────
function abrirEditar(id, titulo, descricao, tipo, url, nicho, categoria, duracao, ordem, ativo) {
    document.getElementById('editTitulo').value    = titulo;
    document.getElementById('editDescricao').value = descricao;
    document.getElementById('editUrl').value       = url || '';
    document.getElementById('editNicho').value     = nicho;
    document.getElementById('editCategoria').value = categoria;
    document.getElementById('editOrdem').value     = ordem;
    document.getElementById('editAtivo').checked   = ativo;
    document.getElementById('formEditar').action   = `/super-admin/videos/${id}`;
    new bootstrap.Modal(document.getElementById('modalEditarVideo')).show();
}

// ─── Copiar link ──────────────────────────────────────────────────────────────
function copiarLink(link, btn) {
    navigator.clipboard.writeText(link).then(() => {
        const orig = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check me-1 text-success"></i> Copiado!';
        btn.classList.add('text-success');
        setTimeout(() => { btn.innerHTML = orig; btn.classList.remove('text-success'); }, 2000);
    });
}
</script>
</body>
</html>
