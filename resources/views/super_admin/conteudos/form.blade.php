@extends('adminlte::page')

@section('title', isset($conteudo) ? 'Editar Conteúdo' : 'Gerar Conteúdo com IA')

@section('content_header')
    <div class="d-flex align-items-center">
        <a href="{{ route('super.admin.conteudos') }}" class="btn btn-sm btn-light mr-3">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="m-0">
                <i class="fas fa-magic text-primary mr-2"></i>
                {{ isset($conteudo) ? 'Editar Conteúdo' : 'Gerar Novo Conteúdo' }}
            </h1>
            <small class="text-muted">Use IA para criar artigos, posts de Instagram e roteiros de TikTok</small>
        </div>
    </div>
@endsection

@section('content')
<div class="container-fluid">
<div class="row">

    {{-- ══════════ COLUNA ESQUERDA: Editor Principal ══════════ --}}
    <div class="col-xl-8 col-lg-7">

        <form method="POST"
              action="{{ isset($conteudo) ? route('super.admin.conteudos.update', $conteudo) : route('super.admin.conteudos.store') }}"
              enctype="multipart/form-data"
              id="formConteudo">
            @csrf
            @if(isset($conteudo)) @method('PUT') @endif

            {{-- Metadados --}}
            <div class="card card-outline card-primary mb-3">
                <div class="card-header"><h3 class="card-title"><i class="fas fa-info-circle mr-1"></i>Informações</h3></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Título <span class="text-danger">*</span></label>
                                <input type="text" name="titulo" id="inputTitulo"
                                       class="form-control @error('titulo') is-invalid @enderror"
                                       value="{{ old('titulo', $conteudo->titulo ?? '') }}"
                                       placeholder="Título do conteúdo" required>
                                @error('titulo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    @foreach(['rascunho' => 'Rascunho', 'revisado' => 'Revisado', 'publicado' => 'Publicado'] as $val => $label)
                                        <option value="{{ $val }}" @selected(old('status', $conteudo->status ?? 'rascunho') === $val)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Formato <span class="text-danger">*</span></label>
                                <select name="formato" id="selectFormato" class="form-control" required>
                                    <option value="artigo"         @selected(old('formato', $conteudo->formato ?? '') === 'artigo')>📰 Artigo / Blog</option>
                                    <option value="post_instagram" @selected(old('formato', $conteudo->formato ?? '') === 'post_instagram')>📸 Post Instagram</option>
                                    <option value="post_tiktok"    @selected(old('formato', $conteudo->formato ?? '') === 'post_tiktok')>🎵 Roteiro TikTok</option>
                                    <option value="legenda_video"  @selected(old('formato', $conteudo->formato ?? '') === 'legenda_video')>🎬 Legenda de Vídeo</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nicho</label>
                                <select name="nicho" id="selectNicho" class="form-control">
                                    <option value="">Geral</option>
                                    @foreach($nichos as $n)
                                        <option value="{{ $n->nicho }}" @selected(old('nicho', $conteudo->nicho ?? '') === $n->nicho)>{{ $n->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Imagem de Capa</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="imagem_capa" id="inputCapa" accept="image/*">
                                <label class="custom-file-label" for="inputCapa">Escolher imagem...</label>
                            </div>
                        </div>
                        @if(isset($conteudo) && $conteudo->imagem_capa)
                            <div class="mt-2">
                                <img src="{{ $conteudo->imagem_capa_url }}" style="height:80px;border-radius:4px;" alt="">
                                <small class="text-muted ml-2">Enviar nova imagem para substituir</small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Editor de Conteúdo Principal (TinyMCE) --}}
            <div class="card card-outline card-primary mb-3" id="cardEditorRico">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-edit mr-1"></i>Conteúdo Principal</h3>
                    <div class="card-tools">
                        <span id="contadorPalavras" class="badge badge-secondary mr-2">0 palavras</span>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="btnCopiarCorpo">
                            <i class="fas fa-copy mr-1"></i>Copiar
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <textarea name="corpo" id="editorCorpo" style="min-height:400px;">{{ old('corpo', $conteudo->corpo ?? '') }}</textarea>
                </div>
            </div>

            {{-- Legenda / Caption (para redes sociais) --}}
            <div class="card card-outline card-secondary mb-3">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-align-left mr-1"></i>Legenda / Caption</h3>
                    <div class="card-tools">
                        <span id="contadorLegenda" class="badge badge-secondary">0 / 2200</span>
                    </div>
                </div>
                <div class="card-body">
                    <textarea name="legenda" id="inputLegenda"
                              class="form-control @error('legenda') is-invalid @enderror"
                              rows="5"
                              placeholder="Versão curta para Instagram, TikTok ou como meta-description do artigo...">{{ old('legenda', $conteudo->legenda ?? '') }}</textarea>
                </div>
            </div>

            {{-- Hashtags --}}
            <div class="card card-outline card-secondary mb-3">
                <div class="card-header"><h3 class="card-title"><i class="fas fa-hashtag mr-1"></i>Hashtags</h3></div>
                <div class="card-body">
                    <input type="text" name="hashtags" id="inputHashtags"
                           class="form-control"
                           value="{{ old('hashtags', $conteudo->hashtags ?? '') }}"
                           placeholder="#gestao #pilates #surf #saas">
                </div>
            </div>

            {{-- Botões de Salvar --}}
            <div class="d-flex align-items-center mb-4">
                <button type="submit" class="btn btn-primary btn-lg mr-2">
                    <i class="fas fa-save mr-1"></i>{{ isset($conteudo) ? 'Atualizar' : 'Salvar Conteúdo' }}
                </button>
                <a href="{{ route('super.admin.conteudos') }}" class="btn btn-light btn-lg">Cancelar</a>
            </div>
        </form>

        {{-- ── Publicação em Redes Sociais ── --}}
        @if(isset($conteudo))
        <div class="card card-outline card-success mb-4">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-share-alt mr-1"></i>Publicar nas Redes Sociais</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    {{-- Instagram --}}
                    <div class="col-md-6 mb-3">
                        <div class="p-3 border rounded" style="border-color:#e1306c!important;">
                            <div class="d-flex align-items-center mb-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center mr-2"
                                     style="width:40px;height:40px;background:linear-gradient(45deg,#f09433,#e6683c,#dc2743,#cc2366,#bc1888);">
                                    <i class="fab fa-instagram text-white"></i>
                                </div>
                                <div>
                                    <strong>Instagram</strong><br>
                                    @if($conteudo->publicado_instagram_em)
                                        <small class="text-success"><i class="fas fa-check-circle mr-1"></i>Publicado em {{ $conteudo->publicado_instagram_em->format('d/m/Y H:i') }}</small>
                                    @else
                                        <small class="text-muted">Não publicado</small>
                                    @endif
                                </div>
                            </div>
                            <p class="small text-muted mb-2">
                                Copie o texto e a legenda, depois publique manualmente no Instagram.
                                Clique em "Registrar" após publicar para marcar como publicado.
                            </p>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-outline-danger mr-1 btn-copiar-legenda">
                                    <i class="fas fa-copy mr-1"></i>Copiar legenda
                                </button>
                                <a href="https://www.instagram.com/" target="_blank"
                                   class="btn btn-sm btn-danger mr-1">
                                    <i class="fab fa-instagram mr-1"></i>Abrir Instagram
                                </a>
                                <button class="btn btn-sm btn-outline-success btn-marcar-publicado"
                                        data-rede="instagram" data-id="{{ $conteudo->id }}">
                                    <i class="fas fa-check mr-1"></i>Registrar
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- TikTok --}}
                    <div class="col-md-6 mb-3">
                        <div class="p-3 border rounded" style="border-color:#010101!important;">
                            <div class="d-flex align-items-center mb-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center mr-2"
                                     style="width:40px;height:40px;background:#010101;">
                                    <i class="fab fa-tiktok text-white"></i>
                                </div>
                                <div>
                                    <strong>TikTok</strong><br>
                                    @if($conteudo->publicado_tiktok_em)
                                        <small class="text-success"><i class="fas fa-check-circle mr-1"></i>Publicado em {{ $conteudo->publicado_tiktok_em->format('d/m/Y H:i') }}</small>
                                    @else
                                        <small class="text-muted">Não publicado</small>
                                    @endif
                                </div>
                            </div>
                            <p class="small text-muted mb-2">
                                Grave seu vídeo usando o roteiro gerado. Copie a legenda e publique no TikTok.
                                Clique em "Registrar" após publicar.
                            </p>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-outline-dark mr-1 btn-copiar-legenda">
                                    <i class="fas fa-copy mr-1"></i>Copiar legenda
                                </button>
                                <a href="https://www.tiktok.com/" target="_blank"
                                   class="btn btn-sm btn-dark mr-1">
                                    <i class="fab fa-tiktok mr-1"></i>Abrir TikTok
                                </a>
                                <button class="btn btn-sm btn-outline-success btn-marcar-publicado"
                                        data-rede="tiktok" data-id="{{ $conteudo->id }}">
                                    <i class="fas fa-check mr-1"></i>Registrar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- WhatsApp / outras redes --}}
                <div class="border-top pt-3">
                    <p class="text-muted small mb-2"><strong>Outras opções de compartilhamento:</strong></p>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="https://wa.me/?text={{ urlencode(($conteudo->titulo ?? '') . "\n\n" . ($conteudo->legenda ?? '') . "\n\n" . ($conteudo->hashtags ?? '')) }}"
                           target="_blank" class="btn btn-sm btn-success">
                            <i class="fab fa-whatsapp mr-1"></i>WhatsApp
                        </a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}"
                           target="_blank" class="btn btn-sm btn-primary">
                            <i class="fab fa-linkedin mr-1"></i>LinkedIn
                        </a>
                        <button class="btn btn-sm btn-secondary btn-copiar-legenda">
                            <i class="fas fa-copy mr-1"></i>Copiar legenda
                        </button>
                        <button class="btn btn-sm btn-outline-secondary btn-copiar-conteudo">
                            <i class="fas fa-align-left mr-1"></i>Copiar conteúdo completo
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif

    </div>{{-- /col-xl-8 --}}

    {{-- ══════════ COLUNA DIREITA: Painel DeepSeek ══════════ --}}
    <div class="col-xl-4 col-lg-5">
        <div class="card shadow-sm" style="background:#0d1117;color:#e6edf3;border:1px solid #30363d;position:sticky;top:70px;">
            <div class="card-header border-bottom" style="background:#161b22;border-color:#30363d;">
                <div class="d-flex align-items-center">
                    <div class="mr-2" style="width:28px;height:28px;background:linear-gradient(135deg,#1e88e5,#8e24aa);border-radius:50%;display:flex;align-items:center;justify-content:center;">
                        <i class="fas fa-robot text-white" style="font-size:13px;"></i>
                    </div>
                    <div>
                        <strong style="font-size:.95rem;">DeepSeek AI</strong>
                        <div style="font-size:.72rem;color:#8b949e;">Geração de conteúdo inteligente</div>
                    </div>
                    <span id="iaStatusDot" class="ml-auto rounded-circle" style="width:10px;height:10px;background:#3fb950;flex-shrink:0;"></span>
                </div>
            </div>
            <div class="card-body" style="font-size:.88rem;">

                {{-- Formato (sincronizado) --}}
                <div class="form-group">
                    <label style="color:#8b949e;font-size:.75rem;text-transform:uppercase;letter-spacing:.5px;">Formato</label>
                    <select id="iaFormato" class="form-control form-control-sm" style="background:#21262d;color:#e6edf3;border-color:#30363d;">
                        <option value="artigo">📰 Artigo / Blog</option>
                        <option value="post_instagram">📸 Post Instagram</option>
                        <option value="post_tiktok">🎵 Roteiro TikTok</option>
                        <option value="legenda_video">🎬 Legenda de Vídeo</option>
                    </select>
                </div>

                {{-- Nicho (sincronizado) --}}
                <div class="form-group">
                    <label style="color:#8b949e;font-size:.75rem;text-transform:uppercase;letter-spacing:.5px;">Nicho do Sistema</label>
                    <select id="iaNicho" class="form-control form-control-sm" style="background:#21262d;color:#e6edf3;border-color:#30363d;">
                        <option value="">Geral</option>
                        @foreach($nichos as $n)
                            <option value="{{ $n->nicho }}">{{ $n->nome }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Tom --}}
                <div class="form-group">
                    <label style="color:#8b949e;font-size:.75rem;text-transform:uppercase;letter-spacing:.5px;">Tom / Estilo</label>
                    <select id="iaTom" class="form-control form-control-sm" style="background:#21262d;color:#e6edf3;border-color:#30363d;">
                        <option value="profissional e envolvente">Profissional e Envolvente</option>
                        <option value="descontraído e inspirador">Descontraído e Inspirador</option>
                        <option value="educativo e direto">Educativo e Direto</option>
                        <option value="persuasivo com urgência">Persuasivo com Urgência</option>
                        <option value="storytelling emocional">Storytelling Emocional</option>
                        <option value="humorístico e leve">Humorístico e Leve</option>
                    </select>
                </div>

                {{-- Tópico / Prompt --}}
                <div class="form-group">
                    <label style="color:#8b949e;font-size:.75rem;text-transform:uppercase;letter-spacing:.5px;">Tópico / Instruções <span style="color:#f85149;">*</span></label>
                    <textarea id="iaTopico" rows="5"
                              class="form-control"
                              style="background:#21262d;color:#e6edf3;border-color:#30363d;resize:vertical;"
                              placeholder="Ex: Como o Pilates Gestão ajuda estúdios a reduzir faltas e aumentar retenção de alunos..."></textarea>
                    <small style="color:#8b949e;">Seja específico: mencione público, objetivo, ângulo do conteúdo.</small>
                </div>

                {{-- Sugestões rápidas --}}
                <div class="mb-3">
                    <label style="color:#8b949e;font-size:.75rem;text-transform:uppercase;letter-spacing:.5px;">Sugestões rápidas</label>
                    <div class="d-flex flex-wrap" style="gap:5px;">
                        @foreach([
                            'Como o sistema aumenta retenção de alunos',
                            'Benefícios de usar software de gestão',
                            'Por que sua academia precisa de um sistema',
                            '5 erros que donos de estúdio cometem',
                            'Automatize cobranças e aumente sua receita',
                        ] as $sugestao)
                        <button type="button" class="btn btn-sm btn-chip" data-topico="{{ $sugestao }}"
                                style="background:#21262d;color:#79c0ff;border:1px solid #30363d;font-size:.72rem;padding:3px 8px;border-radius:12px;">
                            {{ $sugestao }}
                        </button>
                        @endforeach
                    </div>
                </div>

                {{-- Botão Gerar --}}
                <button id="btnGerarIA" class="btn btn-block"
                        style="background:linear-gradient(135deg,#1e88e5,#8e24aa);color:#fff;font-weight:600;border:none;">
                    <i class="fas fa-magic mr-1"></i>
                    <span id="btnGerarIAText">Gerar com IA</span>
                </button>

                {{-- Status --}}
                <div id="iaStatus" class="mt-3" style="display:none;"></div>

                {{-- Prévia rápida --}}
                <div id="iaPreview" style="display:none;margin-top:12px;">
                    <div style="background:#21262d;border:1px solid #30363d;border-radius:6px;padding:10px;">
                        <div style="color:#8b949e;font-size:.72rem;text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px;">Conteúdo gerado</div>
                        <div id="iaPreviewTitulo" style="color:#e6edf3;font-weight:600;font-size:.9rem;margin-bottom:6px;"></div>
                        <div id="iaPreviewLegenda" style="color:#8b949e;font-size:.8rem;line-height:1.4;"></div>
                        <div id="iaPreviewHashtags" style="color:#79c0ff;font-size:.75rem;margin-top:8px;"></div>
                        <div class="mt-2 d-flex" style="gap:6px;">
                            <button id="btnAplicarIA" class="btn btn-sm"
                                    style="background:#238636;color:#fff;border:none;font-size:.8rem;">
                                <i class="fas fa-check mr-1"></i>Aplicar no editor
                            </button>
                            <button id="btnRegenerarIA" class="btn btn-sm"
                                    style="background:#21262d;color:#8b949e;border:1px solid #30363d;font-size:.8rem;">
                                <i class="fas fa-redo mr-1"></i>Regenerar
                            </button>
                        </div>
                    </div>
                </div>

            </div>{{-- /card-body --}}
        </div>{{-- /card dark --}}
    </div>{{-- /col-xl-4 --}}

</div>{{-- /row --}}
</div>{{-- /container-fluid --}}
@endsection

{{-- ═══════════════════════ CSS ═══════════════════════ --}}
@section('css')
<style>
.tox-tinymce { border-radius: 0 0 .25rem .25rem !important; }
.tox .tox-toolbar { background: #f8f9fa !important; }
#cardEditorRico .card-body { overflow: hidden; }
.btn-chip:hover { background: #30363d !important; }
</style>
@endsection

{{-- ═══════════════════════ JS ═══════════════════════ --}}
@section('js')
{{-- TinyMCE via CDN --}}
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
/* ── Inicialização TinyMCE ─────────────────────────────────────── */
let editorInstance = null;

tinymce.init({
    selector: '#editorCorpo',
    language: 'pt_BR',
    language_url: 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/pt_BR.min.js',
    height: 520,
    menubar: true,
    branding: false,
    plugins: [
        'advlist','autolink','lists','link','image','charmap','preview',
        'anchor','searchreplace','visualblocks','code','fullscreen',
        'insertdatetime','media','table','wordcount','codesample'
    ],
    toolbar:
        'undo redo | formatselect | bold italic underline strikethrough | ' +
        'alignleft aligncenter alignright alignjustify | ' +
        'bullist numlist outdent indent | link image media | ' +
        'blockquote codesample | removeformat code fullscreen',
    content_style: `
        body { font-family: "Inter", -apple-system, BlinkMacSystemFont, sans-serif; font-size:16px; line-height:1.7; color:#212529; max-width:720px; margin:0 auto; padding:16px; }
        h1,h2,h3 { font-weight:700; margin-top:1.5em; }
        h2 { font-size:1.5em; border-bottom:2px solid #e9ecef; padding-bottom:.3em; }
        blockquote { border-left:4px solid #4299e1; padding:.5em 1em; background:#f7fafc; margin:1em 0; }
        img { max-width:100%; height:auto; border-radius:6px; }
    `,
    setup: function(editor) {
        editorInstance = editor;
        editor.on('input change keyup', atualizarContador);
    },
    init_instance_callback: atualizarContador,
});

function atualizarContador() {
    if (!editorInstance) return;
    const texto = editorInstance.getContent({ format: 'text' });
    const palavras = texto.trim() ? texto.trim().split(/\s+/).length : 0;
    document.getElementById('contadorPalavras').textContent = palavras.toLocaleString('pt-BR') + ' palavras';
}

/* ── Contador legenda ──────────────────────────────────────────── */
const inputLegenda = document.getElementById('inputLegenda');
const contadorLegenda = document.getElementById('contadorLegenda');
inputLegenda.addEventListener('input', () => {
    const len = inputLegenda.value.length;
    contadorLegenda.textContent = `${len} / 2200`;
    contadorLegenda.className = len > 2000 ? 'badge badge-warning' : (len > 2200 ? 'badge badge-danger' : 'badge badge-secondary');
});

/* ── Sincronizar selects (painel IA ↔ formulário) ───────────────── */
const selectFormato = document.getElementById('selectFormato');
const iaFormato     = document.getElementById('iaFormato');
const selectNicho   = document.getElementById('selectNicho');
const iaNicho       = document.getElementById('iaNicho');

// Inicializa painel IA com valores do formulário
iaFormato.value = selectFormato.value;
iaNicho.value   = selectNicho.value;

selectFormato.addEventListener('change', () => { iaFormato.value = selectFormato.value; });
iaFormato.addEventListener('change',     () => { selectFormato.value = iaFormato.value; });
selectNicho.addEventListener('change',   () => { iaNicho.value = selectNicho.value; });
iaNicho.addEventListener('change',       () => { selectNicho.value = iaNicho.value; });

/* ── Sugestões rápidas ─────────────────────────────────────────── */
document.querySelectorAll('.btn-chip').forEach(btn => {
    btn.addEventListener('click', () => {
        document.getElementById('iaTopico').value = btn.dataset.topico;
    });
});

/* ── Dados do resultado IA (memorizados para "Aplicar") ──────────── */
let iaResultado = null;

/* ── Gerar com IA ─────────────────────────────────────────────── */
document.getElementById('btnGerarIA').addEventListener('click', gerarComIA);
document.getElementById('btnRegenerarIA')?.addEventListener('click', gerarComIA);

async function gerarComIA() {
    const topico = document.getElementById('iaTopico').value.trim();
    if (!topico) {
        mostrarStatus('error', 'Descreva o tópico antes de gerar.');
        return;
    }

    // Loading state
    const btn = document.getElementById('btnGerarIA');
    const btnText = document.getElementById('btnGerarIAText');
    btn.disabled = true;
    btnText.textContent = 'Gerando...';
    document.getElementById('iaStatusDot').style.background = '#f0883e';
    document.getElementById('iaStatus').style.display = 'none';
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

        if (!resp.ok) {
            mostrarStatus('error', data.error || 'Erro desconhecido.');
            return;
        }

        iaResultado = data;

        // Mostrar prévia
        document.getElementById('iaPreviewTitulo').textContent = data.titulo;
        document.getElementById('iaPreviewLegenda').textContent = data.legenda ? data.legenda.substring(0, 200) + '...' : '';
        document.getElementById('iaPreviewHashtags').textContent = data.hashtags;
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

/* ── Aplicar resultado no editor ─────────────────────────────── */
document.getElementById('btnAplicarIA')?.addEventListener('click', () => {
    if (!iaResultado) return;

    // Título
    document.getElementById('inputTitulo').value = iaResultado.titulo || '';

    // Corpo — define no TinyMCE (HTML) ou textarea simples
    if (editorInstance) {
        const corpo = iaResultado.corpo || '';
        // Para artigo: assume HTML; para post/tiktok: converte quebras em <p>
        const isHtml = iaFormato.value === 'artigo' || iaFormato.value === 'legenda_video';
        if (isHtml) {
            editorInstance.setContent(corpo);
        } else {
            // Converte texto simples para parágrafos HTML
            const html = corpo.split('\n\n')
                .filter(p => p.trim())
                .map(p => `<p>${p.replace(/\n/g, '<br>')}</p>`)
                .join('');
            editorInstance.setContent(html);
        }
        atualizarContador();
    }

    // Legenda
    inputLegenda.value = iaResultado.legenda || '';
    inputLegenda.dispatchEvent(new Event('input'));

    // Hashtags
    document.getElementById('inputHashtags').value = iaResultado.hashtags || '';

    // Salvar tópico no campo oculto (para preservar no save)
    // (não há campo hidden para topico no form, mas adicionamos via input oculto)
    let hiddenTopico = document.getElementById('hiddenTopico');
    if (!hiddenTopico) {
        hiddenTopico = document.createElement('input');
        hiddenTopico.type = 'hidden';
        hiddenTopico.name = 'topico';
        hiddenTopico.id  = 'hiddenTopico';
        document.getElementById('formConteudo').appendChild(hiddenTopico);
    }
    hiddenTopico.value = document.getElementById('iaTopico').value;

    mostrarStatus('success', '✓ Aplicado no editor!');

    // Scroll para o editor
    document.getElementById('cardEditorRico').scrollIntoView({ behavior: 'smooth', block: 'start' });
});

/* ── Copiar corpo ────────────────────────────────────────────── */
document.getElementById('btnCopiarCorpo')?.addEventListener('click', () => {
    const texto = editorInstance ? editorInstance.getContent({ format: 'text' }) : document.getElementById('editorCorpo').value;
    navigator.clipboard.writeText(texto).then(() => {
        const btn = document.getElementById('btnCopiarCorpo');
        btn.innerHTML = '<i class="fas fa-check mr-1"></i>Copiado!';
        setTimeout(() => { btn.innerHTML = '<i class="fas fa-copy mr-1"></i>Copiar'; }, 2000);
    });
});

/* ── Copiar legenda ──────────────────────────────────────────── */
document.querySelectorAll('.btn-copiar-legenda').forEach(btn => {
    btn.addEventListener('click', () => {
        const legenda = document.getElementById('inputLegenda')?.value
            || '{{ addslashes($conteudo->legenda ?? '') }}';
        const hashtags = document.getElementById('inputHashtags')?.value
            || '{{ addslashes($conteudo->hashtags ?? '') }}';
        const texto = legenda + (hashtags ? '\n\n' + hashtags : '');
        navigator.clipboard.writeText(texto).then(() => {
            btn.innerHTML = '<i class="fas fa-check mr-1"></i>Copiado!';
            setTimeout(() => { btn.innerHTML = btn.innerHTML.replace('Copiado!', btn.dataset.orig || 'Copiar legenda'); }, 2000);
        });
    });
});

/* ── Copiar conteúdo completo ────────────────────────────────── */
document.querySelectorAll('.btn-copiar-conteudo').forEach(btn => {
    btn.addEventListener('click', () => {
        const corpo   = editorInstance ? editorInstance.getContent({ format: 'text' }) : '';
        const legenda = document.getElementById('inputLegenda')?.value || '{{ addslashes($conteudo->legenda ?? '') }}';
        const hashtags = document.getElementById('inputHashtags')?.value || '{{ addslashes($conteudo->hashtags ?? '') }}';
        const tudo = corpo + (legenda ? '\n\n---\n' + legenda : '') + (hashtags ? '\n\n' + hashtags : '');
        navigator.clipboard.writeText(tudo).then(() => {
            btn.innerHTML = '<i class="fas fa-check mr-1"></i>Copiado!';
            setTimeout(() => { btn.innerHTML = '<i class="fas fa-align-left mr-1"></i>Copiar conteúdo completo'; }, 2000);
        });
    });
});

/* ── Marcar como publicado ───────────────────────────────────── */
document.querySelectorAll('.btn-marcar-publicado').forEach(btn => {
    btn.addEventListener('click', async function() {
        const rede = this.dataset.rede;
        const id   = this.dataset.id;
        const redesNome = rede === 'instagram' ? 'Instagram' : 'TikTok';

        if (!confirm(`Confirmar publicação no ${redesNome}? Isso registrará a data/hora atual.`)) return;

        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Salvando...';

        try {
            const resp = await fetch(`/super-admin/conteudos/${id}/publicado`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ rede }),
            });
            const data = await resp.json();
            if (data.ok) {
                btn.innerHTML = `<i class="fas fa-check-circle mr-1"></i>Publicado em ${data.publicado_em}`;
                btn.classList.replace('btn-outline-success', 'btn-success');
            }
        } catch(e) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-check mr-1"></i>Registrar';
            alert('Erro: ' + e.message);
        }
    });
});

/* ── Utilitário: exibir status ───────────────────────────────── */
function mostrarStatus(tipo, msg) {
    const el = document.getElementById('iaStatus');
    const icone = tipo === 'success' ? '✓' : '✗';
    const cor   = tipo === 'success' ? '#3fb950' : '#f85149';
    el.style.display = 'block';
    el.innerHTML = `<span style="color:${cor};font-size:.82rem;">${icone} ${msg}</span>`;
}

/* ── Custom file input label ─────────────────────────────────── */
document.getElementById('inputCapa')?.addEventListener('change', function() {
    const label = this.nextElementSibling;
    label.textContent = this.files[0]?.name || 'Escolher imagem...';
});
</script>
@endsection
