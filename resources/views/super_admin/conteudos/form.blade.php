<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin — {{ isset($conteudo) ? 'Editar Conteúdo' : 'Gerar Conteúdo com IA' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f4f6fb; font-family: 'Segoe UI', sans-serif; }
        .sidebar { width: 240px; min-height: 100vh; background: #1a1f36; position: fixed; top: 0; left: 0; overflow-y: auto; }
        .sidebar .logo { padding: 28px 24px 16px; color: #fff; font-size: 1.1rem; font-weight: 700; border-bottom: 1px solid #2d3354; }
        .sidebar .logo small { display: block; font-size: .7rem; color: #8892b0; margin-top: 2px; }
        .sidebar .nav-link { color: #8892b0; padding: 10px 24px; font-size: .88rem; display: flex; align-items: center; gap: 10px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background: #2d3354; }
        .sidebar .nav-link i { width: 16px; }
        .sidebar .nav-section { padding: 16px 24px 4px; font-size: .68rem; text-transform: uppercase; letter-spacing: .1em; color: #4a5568; }
        .main { margin-left: 240px; padding: 32px; }

        /* Card seções */
        .section-card { background: #fff; border-radius: 14px; border: 1px solid #e9ecf0; margin-bottom: 20px; overflow: hidden; }
        .section-card .section-header { padding: 14px 20px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between; }
        .section-card .section-title { font-weight: 700; font-size: .9rem; color: #1a1f36; margin: 0; }
        .section-card .section-body { padding: 20px; }

        /* Painel IA */
        .ia-panel { background: #0d1117; color: #e6edf3; border: 1px solid #30363d; border-radius: 14px; position: sticky; top: 20px; }
        .ia-panel .ia-header { background: #161b22; border-bottom: 1px solid #30363d; padding: 14px 18px; border-radius: 14px 14px 0 0; }
        .ia-panel .ia-body { padding: 18px; }
        .ia-panel label { color: #8b949e; font-size: .72rem; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 4px; display: block; }
        .ia-panel .form-control, .ia-panel .form-select {
            background: #21262d; color: #e6edf3; border-color: #30363d; font-size: .88rem;
        }
        .ia-panel .form-control:focus, .ia-panel .form-select:focus {
            background: #21262d; color: #e6edf3; border-color: #58a6ff; box-shadow: 0 0 0 3px rgba(88,166,255,.15);
        }
        .ia-panel .form-control::placeholder { color: #484f58; }
        .ia-panel option { background: #21262d; }
        .btn-chip {
            background: #21262d; color: #79c0ff; border: 1px solid #30363d;
            font-size: .72rem; padding: 3px 10px; border-radius: 20px; cursor: pointer;
            transition: background .15s;
        }
        .btn-chip:hover { background: #30363d; }
        .ia-status-dot { width: 10px; height: 10px; border-radius: 50%; background: #3fb950; flex-shrink: 0; }
        .ia-preview { background: #21262d; border: 1px solid #30363d; border-radius: 8px; padding: 12px; margin-top: 12px; }

        /* Redes sociais */
        .rede-card { border-radius: 12px; border: 1px solid #e9ecf0; padding: 16px; background: #fff; }
        .rede-card.instagram { border-color: #e1306c33; }
        .rede-card.tiktok    { border-color: #01010133; }
        .rede-icon { width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }

        /* Contador */
        #contadorPalavras { font-size: .75rem; }
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

    {{-- Page header --}}
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('super.admin.conteudos') }}" class="btn btn-sm btn-outline-secondary me-3">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h4 class="mb-0 fw-bold">
                <i class="fas fa-magic text-primary me-2"></i>
                {{ isset($conteudo) ? 'Editar Conteúdo' : 'Gerar Novo Conteúdo' }}
            </h4>
            <small class="text-muted">Use IA para criar artigos, posts de Instagram e roteiros de TikTok</small>
        </div>
    </div>

    <div class="row g-4">

        {{-- ══════ COL ESQUERDA: Editor ══════ --}}
        <div class="col-xl-8 col-lg-7">

            <form method="POST"
                  action="{{ isset($conteudo) ? route('super.admin.conteudos.update', $conteudo) : route('super.admin.conteudos.store') }}"
                  enctype="multipart/form-data"
                  id="formConteudo">
                @csrf
                @if(isset($conteudo)) @method('PUT') @endif

                {{-- Metadados --}}
                <div class="section-card">
                    <div class="section-header">
                        <h5 class="section-title"><i class="fas fa-info-circle text-primary me-2"></i>Informações</h5>
                    </div>
                    <div class="section-body">
                        {{-- Link público (só na edição de artigo publicado) --}}
                        @if(isset($conteudo) && $conteudo->status === 'publicado' && $conteudo->formato === 'artigo')
                        <div class="alert mb-3 d-flex align-items-center justify-content-between"
                             style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;padding:12px 16px;">
                            <div>
                                <span style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#15803d;">
                                    <i class="fas fa-globe me-1"></i> Artigo publicado — link compartilhável
                                </span>
                                <div style="font-size:.88rem;color:#166534;margin-top:2px;word-break:break-all;">
                                    {{ $conteudo->url_publica }}
                                </div>
                            </div>
                            <div class="d-flex gap-2 flex-shrink-0 ms-3">
                                <button type="button" class="btn btn-sm btn-success" id="btnCopiarLinkAdmin"
                                        data-link="{{ $conteudo->url_publica }}">
                                    <i class="fas fa-copy me-1"></i>Copiar
                                </button>
                                <a href="{{ $conteudo->url_publica }}" target="_blank"
                                   class="btn btn-sm btn-outline-success">
                                    <i class="fas fa-external-link-alt me-1"></i>Ver
                                </a>
                                <a href="https://wa.me/?text={{ urlencode($conteudo->titulo . "\n\n" . $conteudo->url_publica) }}"
                                   target="_blank" class="btn btn-sm" style="background:#25d366;color:#fff;">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            </div>
                        </div>
                        @elseif(isset($conteudo) && $conteudo->formato === 'artigo')
                        <div class="alert mb-3 d-flex align-items-center gap-2"
                             style="background:#fffbeb;border:1px solid #fde68a;border-radius:10px;padding:12px 16px;font-size:.85rem;color:#92400e;">
                            <i class="fas fa-info-circle"></i>
                            Mude o <strong>Status</strong> para <strong>Publicado</strong> para ativar o link público compartilhável.
                        </div>
                        @endif

                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">Título <span class="text-danger">*</span></label>
                                <input type="text" name="titulo" id="inputTitulo"
                                       class="form-control @error('titulo') is-invalid @enderror"
                                       value="{{ old('titulo', $conteudo->titulo ?? '') }}"
                                       placeholder="Título do conteúdo" required>
                                @error('titulo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Status</label>
                                <select name="status" class="form-select">
                                    <option value="rascunho"  @selected(old('status', $conteudo->status ?? 'rascunho') === 'rascunho')>Rascunho</option>
                                    <option value="revisado"  @selected(old('status', $conteudo->status ?? '') === 'revisado')>Revisado</option>
                                    <option value="publicado" @selected(old('status', $conteudo->status ?? '') === 'publicado')>Publicado</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Formato <span class="text-danger">*</span></label>
                                <select name="formato" id="selectFormato" class="form-select" required>
                                    <option value="artigo"         @selected(old('formato', $conteudo->formato ?? '') === 'artigo')>📰 Artigo / Blog</option>
                                    <option value="post_instagram" @selected(old('formato', $conteudo->formato ?? '') === 'post_instagram')>📸 Post Instagram</option>
                                    <option value="post_tiktok"    @selected(old('formato', $conteudo->formato ?? '') === 'post_tiktok')>🎵 Roteiro TikTok</option>
                                    <option value="legenda_video"  @selected(old('formato', $conteudo->formato ?? '') === 'legenda_video')>🎬 Legenda de Vídeo</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nicho</label>
                                <select name="nicho" id="selectNicho" class="form-select">
                                    <option value="">Geral</option>
                                    @foreach($nichos as $n)
                                        <option value="{{ $n->nicho }}" @selected(old('nicho', $conteudo->nicho ?? '') === $n->nicho)>{{ $n->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Autor</label>
                                <input type="text" name="autor" class="form-control"
                                       value="{{ old('autor', $conteudo->autor ?? '') }}"
                                       placeholder="Nome do autor (opcional)">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Meta descrição <small class="text-muted">(SEO)</small></label>
                                <input type="text" name="meta_descricao" class="form-control"
                                       value="{{ old('meta_descricao', $conteudo->meta_descricao ?? '') }}"
                                       placeholder="Descrição para Google / WhatsApp preview (até 300 chars)" maxlength="300">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Imagem de Capa</label>
                                <input type="file" class="form-control" name="imagem_capa" id="inputCapa" accept="image/*">
                                @if(isset($conteudo) && $conteudo->imagem_capa)
                                    <div class="mt-2 d-flex align-items-center gap-2">
                                        <img src="{{ \Illuminate\Support\Facades\Storage::url($conteudo->imagem_capa) }}"
                                             style="height:64px;border-radius:6px;" alt="">
                                        <small class="text-muted">Enviar nova imagem para substituir</small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Editor de Conteúdo (TinyMCE) --}}
                <div class="section-card">
                    <div class="section-header">
                        <h5 class="section-title"><i class="fas fa-edit text-primary me-2"></i>Conteúdo Principal</h5>
                        <div class="d-flex align-items-center gap-2">
                            <span id="contadorPalavras" class="badge bg-secondary">0 palavras</span>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="btnCopiarCorpo">
                                <i class="fas fa-copy me-1"></i>Copiar
                            </button>
                        </div>
                    </div>
                    <div class="p-0">
                        <textarea name="corpo" id="editorCorpo">{{ old('corpo', $conteudo->corpo ?? '') }}</textarea>
                    </div>
                </div>

                {{-- Legenda --}}
                <div class="section-card">
                    <div class="section-header">
                        <h5 class="section-title"><i class="fas fa-align-left text-secondary me-2"></i>Legenda / Caption</h5>
                        <span id="contadorLegenda" class="badge bg-secondary">0 / 2200</span>
                    </div>
                    <div class="section-body">
                        <textarea name="legenda" id="inputLegenda"
                                  class="form-control @error('legenda') is-invalid @enderror"
                                  rows="5"
                                  placeholder="Versão curta para Instagram, TikTok ou meta-description do artigo...">{{ old('legenda', $conteudo->legenda ?? '') }}</textarea>
                    </div>
                </div>

                {{-- Hashtags --}}
                <div class="section-card">
                    <div class="section-header">
                        <h5 class="section-title"><i class="fas fa-hashtag text-secondary me-2"></i>Hashtags</h5>
                    </div>
                    <div class="section-body">
                        <input type="text" name="hashtags" id="inputHashtags"
                               class="form-control"
                               value="{{ old('hashtags', $conteudo->hashtags ?? '') }}"
                               placeholder="#gestao #pilates #surf #saas">
                    </div>
                </div>

                {{-- Salvar --}}
                <div class="d-flex gap-2 mb-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>{{ isset($conteudo) ? 'Atualizar' : 'Salvar Conteúdo' }}
                    </button>
                    <a href="{{ route('super.admin.conteudos') }}" class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </form>

            {{-- ── Publicação em Redes Sociais (apenas na edição) ── --}}
            @if(isset($conteudo))
            <div class="section-card">
                <div class="section-header">
                    <h5 class="section-title"><i class="fas fa-share-alt text-success me-2"></i>Publicar nas Redes Sociais</h5>
                </div>
                <div class="section-body">
                    <div class="row g-3">
                        {{-- Instagram --}}
                        <div class="col-md-6">
                            <div class="rede-card instagram">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <div class="rede-icon" style="background:linear-gradient(45deg,#f09433,#e6683c,#dc2743,#cc2366,#bc1888);">
                                        <i class="fab fa-instagram text-white"></i>
                                    </div>
                                    <div>
                                        <strong>Instagram</strong><br>
                                        @if($conteudo->publicado_instagram_em)
                                            <small class="text-success"><i class="fas fa-check-circle me-1"></i>Publicado em {{ \Carbon\Carbon::parse($conteudo->publicado_instagram_em)->format('d/m/Y H:i') }}</small>
                                        @else
                                            <small class="text-muted">Não publicado</small>
                                        @endif
                                    </div>
                                </div>
                                <p class="small text-muted mb-3">Copie a legenda, publique manualmente no Instagram e clique em "Registrar" para marcar como publicado.</p>
                                <div class="d-flex gap-2 flex-wrap">
                                    <button class="btn btn-sm btn-outline-danger btn-copiar-legenda">
                                        <i class="fas fa-copy me-1"></i>Copiar legenda
                                    </button>
                                    <a href="https://www.instagram.com/" target="_blank" class="btn btn-sm btn-danger">
                                        <i class="fab fa-instagram me-1"></i>Abrir
                                    </a>
                                    <button class="btn btn-sm btn-outline-success btn-marcar-publicado"
                                            data-rede="instagram" data-id="{{ $conteudo->id }}">
                                        <i class="fas fa-check me-1"></i>Registrar
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- TikTok --}}
                        <div class="col-md-6">
                            <div class="rede-card tiktok">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <div class="rede-icon" style="background:#010101;">
                                        <i class="fab fa-tiktok text-white"></i>
                                    </div>
                                    <div>
                                        <strong>TikTok</strong><br>
                                        @if($conteudo->publicado_tiktok_em)
                                            <small class="text-success"><i class="fas fa-check-circle me-1"></i>Publicado em {{ \Carbon\Carbon::parse($conteudo->publicado_tiktok_em)->format('d/m/Y H:i') }}</small>
                                        @else
                                            <small class="text-muted">Não publicado</small>
                                        @endif
                                    </div>
                                </div>
                                <p class="small text-muted mb-3">Grave seu vídeo usando o roteiro gerado. Copie a legenda, publique no TikTok e clique em "Registrar".</p>
                                <div class="d-flex gap-2 flex-wrap">
                                    <button class="btn btn-sm btn-outline-dark btn-copiar-legenda">
                                        <i class="fas fa-copy me-1"></i>Copiar legenda
                                    </button>
                                    <a href="https://www.tiktok.com/" target="_blank" class="btn btn-sm btn-dark">
                                        <i class="fab fa-tiktok me-1"></i>Abrir
                                    </a>
                                    <button class="btn btn-sm btn-outline-success btn-marcar-publicado"
                                            data-rede="tiktok" data-id="{{ $conteudo->id }}">
                                        <i class="fas fa-check me-1"></i>Registrar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Outros compartilhamentos --}}
                    <div class="border-top pt-3 mt-2">
                        <p class="text-muted small mb-2 fw-semibold">Outros compartilhamentos:</p>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="https://wa.me/?text={{ urlencode(($conteudo->titulo ?? '') . "\n\n" . ($conteudo->legenda ?? '') . "\n\n" . ($conteudo->hashtags ?? '')) }}"
                               target="_blank" class="btn btn-sm btn-success">
                                <i class="fab fa-whatsapp me-1"></i>WhatsApp
                            </a>
                            <a href="https://www.linkedin.com/feed/?shareActive=true&text={{ urlencode(($conteudo->titulo ?? '') . "\n\n" . ($conteudo->legenda ?? '')) }}"
                               target="_blank" class="btn btn-sm btn-primary">
                                <i class="fab fa-linkedin me-1"></i>LinkedIn
                            </a>
                            <button class="btn btn-sm btn-outline-secondary btn-copiar-legenda">
                                <i class="fas fa-copy me-1"></i>Copiar legenda
                            </button>
                            <button class="btn btn-sm btn-outline-secondary btn-copiar-conteudo">
                                <i class="fas fa-align-left me-1"></i>Copiar conteúdo completo
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endif

        </div>{{-- /col esquerda --}}

        {{-- ══════ COL DIREITA: Painel IA ══════ --}}
        <div class="col-xl-4 col-lg-5">
            <div class="ia-panel">
                <div class="ia-header d-flex align-items-center gap-2">
                    <div style="width:30px;height:30px;background:linear-gradient(135deg,#1e88e5,#8e24aa);border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fas fa-robot text-white" style="font-size:13px;"></i>
                    </div>
                    <div>
                        <div style="font-weight:700;font-size:.93rem;">DeepSeek AI</div>
                        <div style="font-size:.72rem;color:#8b949e;">Geração de conteúdo inteligente</div>
                    </div>
                    <span id="iaStatusDot" class="ia-status-dot ms-auto"></span>
                </div>

                <div class="ia-body">

                    <div class="mb-3">
                        <label>Formato</label>
                        <select id="iaFormato" class="form-select form-select-sm">
                            <option value="artigo">📰 Artigo / Blog</option>
                            <option value="post_instagram">📸 Post Instagram</option>
                            <option value="post_tiktok">🎵 Roteiro TikTok</option>
                            <option value="legenda_video">🎬 Legenda de Vídeo</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Nicho do Sistema</label>
                        <select id="iaNicho" class="form-select form-select-sm">
                            <option value="">Geral</option>
                            @foreach($nichos as $n)
                                <option value="{{ $n->nicho }}">{{ $n->nome }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Tom / Estilo</label>
                        <select id="iaTom" class="form-select form-select-sm">
                            <option value="profissional e envolvente">Profissional e Envolvente</option>
                            <option value="descontraído e inspirador">Descontraído e Inspirador</option>
                            <option value="educativo e direto">Educativo e Direto</option>
                            <option value="persuasivo com urgência">Persuasivo com Urgência</option>
                            <option value="storytelling emocional">Storytelling Emocional</option>
                            <option value="humorístico e leve">Humorístico e Leve</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Tópico / Instruções <span style="color:#f85149;">*</span></label>
                        <textarea id="iaTopico" rows="5" class="form-control"
                                  placeholder="Ex: Como o Pilates Gestão ajuda estúdios a reduzir faltas e aumentar retenção de alunos..."></textarea>
                        <div style="color:#8b949e;font-size:.72rem;margin-top:4px;">Seja específico: público, objetivo, ângulo do conteúdo.</div>
                    </div>

                    {{-- Sugestões rápidas --}}
                    <div class="mb-3">
                        <label>Sugestões rápidas</label>
                        <div class="d-flex flex-wrap" style="gap:5px;">
                            @foreach([
                                'Como o sistema aumenta retenção de alunos',
                                'Benefícios de usar software de gestão',
                                'Por que sua academia precisa de um sistema',
                                '5 erros que donos de estúdio cometem',
                                'Automatize cobranças e aumente sua receita',
                            ] as $sugestao)
                            <button type="button" class="btn-chip" data-topico="{{ $sugestao }}">{{ $sugestao }}</button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Botão Gerar --}}
                    <button id="btnGerarIA" type="button" class="btn w-100 fw-semibold"
                            style="background:linear-gradient(135deg,#1e88e5,#8e24aa);color:#fff;border:none;">
                        <i class="fas fa-magic me-1"></i>
                        <span id="btnGerarIAText">Gerar com IA</span>
                    </button>

                    {{-- Status --}}
                    <div id="iaStatus" style="display:none;margin-top:10px;font-size:.82rem;"></div>

                    {{-- Prévia --}}
                    <div id="iaPreview" style="display:none;">
                        <div class="ia-preview">
                            <div style="color:#8b949e;font-size:.72rem;text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px;">Conteúdo gerado</div>
                            <div id="iaPreviewTitulo" style="color:#e6edf3;font-weight:700;font-size:.88rem;margin-bottom:6px;"></div>
                            <div id="iaPreviewLegenda" style="color:#8b949e;font-size:.8rem;line-height:1.4;"></div>
                            <div id="iaPreviewHashtags" style="color:#79c0ff;font-size:.73rem;margin-top:8px;word-break:break-all;"></div>
                            <div class="d-flex gap-2 mt-3">
                                <button id="btnAplicarIA" type="button" class="btn btn-sm"
                                        style="background:#238636;color:#fff;border:none;font-size:.8rem;">
                                    <i class="fas fa-check me-1"></i>Aplicar no editor
                                </button>
                                <button id="btnRegenerarIA" type="button" class="btn btn-sm"
                                        style="background:#21262d;color:#8b949e;border:1px solid #30363d;font-size:.8rem;">
                                    <i class="fas fa-redo me-1"></i>Regenerar
                                </button>
                            </div>
                        </div>
                    </div>

                </div>{{-- /ia-body --}}
            </div>{{-- /ia-panel --}}
        </div>{{-- /col direita --}}

    </div>{{-- /row --}}
</div>{{-- /main --}}

{{-- TinyMCE --}}
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
/* ── TinyMCE ─────────────────────────────────────────────── */
let editorInstance = null;

tinymce.init({
    selector: '#editorCorpo',
    language: 'pt_BR',
    language_url: 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/pt_BR.min.js',
    height: 500,
    menubar: true,
    branding: false,
    promotion: false,
    plugins: [
        'advlist','autolink','lists','link','image','charmap','preview',
        'anchor','searchreplace','visualblocks','code','fullscreen',
        'insertdatetime','media','table','wordcount'
    ],
    toolbar:
        'undo redo | formatselect | bold italic underline | ' +
        'alignleft aligncenter alignright alignjustify | ' +
        'bullist numlist outdent indent | link image | ' +
        'blockquote | removeformat code fullscreen',
    content_style: `
        body {
            font-family: 'Segoe UI', sans-serif; font-size: 16px;
            line-height: 1.7; color: #212529;
            max-width: 720px; margin: 0 auto; padding: 20px;
        }
        h1,h2,h3 { font-weight: 700; margin-top: 1.4em; }
        h2 { border-bottom: 2px solid #e9ecef; padding-bottom: .3em; }
        blockquote { border-left: 4px solid #4299e1; padding: .5em 1em; background: #f7fafc; margin: 1em 0; }
        img { max-width: 100%; height: auto; border-radius: 6px; }
    `,
    setup(editor) {
        editorInstance = editor;
        editor.on('input change keyup', atualizarContador);
    },
    init_instance_callback: atualizarContador,
});

function atualizarContador() {
    if (!editorInstance) return;
    const texto = editorInstance.getContent({ format: 'text' }).trim();
    const n = texto ? texto.split(/\s+/).length : 0;
    document.getElementById('contadorPalavras').textContent = n.toLocaleString('pt-BR') + ' palavras';
}

/* ── Contador legenda ────────────────────────────────────── */
const inputLegenda   = document.getElementById('inputLegenda');
const contadorLeg    = document.getElementById('contadorLegenda');
inputLegenda.addEventListener('input', () => {
    const n = inputLegenda.value.length;
    contadorLeg.textContent = `${n} / 2200`;
    contadorLeg.className = n > 2000 ? 'badge bg-warning text-dark' : 'badge bg-secondary';
});

/* ── Sincronizar selects ──────────────────────────────────── */
const selectFormato = document.getElementById('selectFormato');
const iaFormato     = document.getElementById('iaFormato');
const selectNicho   = document.getElementById('selectNicho');
const iaNicho       = document.getElementById('iaNicho');

iaFormato.value = selectFormato.value;
iaNicho.value   = selectNicho.value;

selectFormato.addEventListener('change', () => iaFormato.value = selectFormato.value);
iaFormato.addEventListener('change',     () => selectFormato.value = iaFormato.value);
selectNicho.addEventListener('change',   () => iaNicho.value = selectNicho.value);
iaNicho.addEventListener('change',       () => selectNicho.value = iaNicho.value);

/* ── Sugestões rápidas ───────────────────────────────────── */
document.querySelectorAll('.btn-chip').forEach(btn =>
    btn.addEventListener('click', () => document.getElementById('iaTopico').value = btn.dataset.topico)
);

/* ── Gerar com IA ────────────────────────────────────────── */
let iaResultado = null;

async function gerarComIA() {
    const topico = document.getElementById('iaTopico').value.trim();
    if (!topico) { mostrarStatus('error', 'Descreva o tópico antes de gerar.'); return; }

    const btn     = document.getElementById('btnGerarIA');
    const btnText = document.getElementById('btnGerarIAText');
    btn.disabled  = true;
    btnText.textContent = 'Gerando...';
    document.getElementById('iaStatusDot').style.background = '#f0883e';
    document.getElementById('iaStatus').style.display  = 'none';
    document.getElementById('iaPreview').style.display = 'none';

    try {
        const resp = await fetch('{{ route('super.admin.conteudos.gerar-ia') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                topico:  topico,
                formato: iaFormato.value,
                nicho:   iaNicho.value,
                tom:     document.getElementById('iaTom').value,
            }),
        });

        const data = await resp.json();
        if (!resp.ok) { mostrarStatus('error', data.error || 'Erro desconhecido.'); return; }

        iaResultado = data;

        document.getElementById('iaPreviewTitulo').textContent  = data.titulo || '';
        document.getElementById('iaPreviewLegenda').textContent = (data.legenda || '').substring(0, 200) + (data.legenda?.length > 200 ? '...' : '');
        document.getElementById('iaPreviewHashtags').textContent = data.hashtags || '';
        document.getElementById('iaPreview').style.display = 'block';
        document.getElementById('iaStatusDot').style.background = '#3fb950';
        mostrarStatus('success', 'Conteúdo gerado! Clique em "Aplicar no editor".');

    } catch (e) {
        mostrarStatus('error', 'Erro de conexão: ' + e.message);
        document.getElementById('iaStatusDot').style.background = '#f85149';
    } finally {
        btn.disabled = false;
        btnText.textContent = 'Gerar com IA';
    }
}

document.getElementById('btnGerarIA').addEventListener('click', gerarComIA);
document.getElementById('btnRegenerarIA').addEventListener('click', gerarComIA);

/* ── Aplicar no editor ───────────────────────────────────── */
document.getElementById('btnAplicarIA').addEventListener('click', () => {
    if (!iaResultado) return;

    document.getElementById('inputTitulo').value = iaResultado.titulo || '';

    if (editorInstance) {
        const corpo = iaResultado.corpo || '';
        const isHtml = ['artigo','legenda_video'].includes(iaFormato.value);
        if (isHtml) {
            editorInstance.setContent(corpo);
        } else {
            const html = corpo.split('\n\n').filter(p => p.trim())
                .map(p => `<p>${p.replace(/\n/g, '<br>')}</p>`).join('');
            editorInstance.setContent(html);
        }
        atualizarContador();
    }

    inputLegenda.value = iaResultado.legenda || '';
    inputLegenda.dispatchEvent(new Event('input'));
    document.getElementById('inputHashtags').value = iaResultado.hashtags || '';

    // Salvar tópico como campo hidden
    let h = document.getElementById('hiddenTopico');
    if (!h) {
        h = document.createElement('input');
        h.type = 'hidden'; h.name = 'topico'; h.id = 'hiddenTopico';
        document.getElementById('formConteudo').appendChild(h);
    }
    h.value = document.getElementById('iaTopico').value;

    mostrarStatus('success', '✓ Aplicado no editor!');
    document.getElementById('editorCorpo').closest('.section-card')
        .scrollIntoView({ behavior: 'smooth', block: 'start' });
});

/* ── Copiar corpo ────────────────────────────────────────── */
document.getElementById('btnCopiarCorpo').addEventListener('click', () => {
    const texto = editorInstance ? editorInstance.getContent({ format: 'text' }) : '';
    navigator.clipboard.writeText(texto).then(() => {
        const btn = document.getElementById('btnCopiarCorpo');
        btn.innerHTML = '<i class="fas fa-check me-1"></i>Copiado!';
        setTimeout(() => btn.innerHTML = '<i class="fas fa-copy me-1"></i>Copiar', 2000);
    });
});

/* ── Copiar legenda ──────────────────────────────────────── */
document.querySelectorAll('.btn-copiar-legenda').forEach(btn => {
    btn.addEventListener('click', () => {
        const leg  = inputLegenda.value || '';
        const hash = document.getElementById('inputHashtags').value || '';
        navigator.clipboard.writeText(leg + (hash ? '\n\n' + hash : '')).then(() => {
            const orig = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check me-1"></i>Copiado!';
            setTimeout(() => btn.innerHTML = orig, 2000);
        });
    });
});

/* ── Copiar conteúdo completo ────────────────────────────── */
document.querySelectorAll('.btn-copiar-conteudo').forEach(btn => {
    btn.addEventListener('click', () => {
        const corpo   = editorInstance ? editorInstance.getContent({ format: 'text' }) : '';
        const leg     = inputLegenda.value;
        const hash    = document.getElementById('inputHashtags').value;
        const tudo    = corpo + (leg ? '\n\n---\n' + leg : '') + (hash ? '\n\n' + hash : '');
        navigator.clipboard.writeText(tudo).then(() => {
            const orig = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check me-1"></i>Copiado!';
            setTimeout(() => btn.innerHTML = orig, 2000);
        });
    });
});

/* ── Marcar como publicado ───────────────────────────────── */
document.querySelectorAll('.btn-marcar-publicado').forEach(btn => {
    btn.addEventListener('click', async function() {
        const rede = this.dataset.rede;
        const id   = this.dataset.id;
        if (!confirm(`Registrar publicação no ${rede === 'instagram' ? 'Instagram' : 'TikTok'}?`)) return;

        this.disabled = true;
        this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Salvando...';

        try {
            const resp = await fetch(`/super-admin/conteudos/${id}/publicado`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                body: JSON.stringify({ rede }),
            });
            const data = await resp.json();
            if (data.ok) {
                this.innerHTML = `<i class="fas fa-check-circle me-1"></i>Publicado em ${data.publicado_em}`;
                this.classList.replace('btn-outline-success', 'btn-success');
            }
        } catch(e) {
            this.disabled = false;
            this.innerHTML = '<i class="fas fa-check me-1"></i>Registrar';
        }
    });
});

/* ── Copiar link público ─────────────────────────────────── */
document.getElementById('btnCopiarLinkAdmin')?.addEventListener('click', function() {
    navigator.clipboard.writeText(this.dataset.link).then(() => {
        const orig = this.innerHTML;
        this.innerHTML = '<i class="fas fa-check me-1"></i>Copiado!';
        setTimeout(() => this.innerHTML = orig, 2000);
    });
});

/* ── Utilitário ──────────────────────────────────────────── */
function mostrarStatus(tipo, msg) {
    const el = document.getElementById('iaStatus');
    const cor = tipo === 'success' ? '#3fb950' : '#f85149';
    el.style.display = 'block';
    el.innerHTML = `<span style="color:${cor};">${msg}</span>`;
}
</script>
</body>
</html>
