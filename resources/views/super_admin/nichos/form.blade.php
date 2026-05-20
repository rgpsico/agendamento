<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin — {{ $nicho->exists ? 'Editar' : 'Novo' }} Nicho</title>
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
        .main { margin-left: 240px; padding: 32px; }
        .form-card { background: #fff; border-radius: 14px; border: 1px solid #e9ecf0; padding: 28px; }
        .section-title { font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: #8892b0; margin-bottom: 16px; padding-bottom: 8px; border-bottom: 1px solid #e9ecf0; }
        .preview-gradient { height: 48px; border-radius: 10px; transition: all .3s; }
        .upload-area { border: 2px dashed #e2e8f0; border-radius: 10px; padding: 20px; text-align: center; cursor: pointer; transition: border-color .2s; }
        .upload-area:hover { border-color: #6366f1; }
        .upload-area img { max-height: 120px; object-fit: cover; border-radius: 8px; }
        .color-input-wrap { display: flex; align-items: center; gap: 10px; }
        .color-input-wrap input[type=color] { width: 44px; height: 44px; padding: 2px; border-radius: 8px; border: 1px solid #e2e8f0; cursor: pointer; }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="logo"><i class="fas fa-layer-group me-2"></i> Super Admin<small>{{ auth()->user()->email }}</small></div>
    <nav class="mt-2">
        <a href="{{ route('super.admin.index') }}"    class="nav-link"><i class="fas fa-chart-pie"></i> Dashboard</a>
        <a href="{{ route('super.admin.clientes') }}" class="nav-link"><i class="fas fa-building"></i> Clientes</a>
        <a href="{{ route('super.admin.nichos') }}"   class="nav-link active"><i class="fas fa-palette"></i> Nichos</a>
        <a href="{{ route('home') }}" class="nav-link mt-3"><i class="fas fa-arrow-left"></i> Voltar</a>
    </nav>
</div>

<div class="main">
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('super.admin.nichos') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h4 class="mb-0 fw-bold">{{ $nicho->exists ? 'Editar: ' . $nicho->nome : 'Novo Nicho' }}</h4>
            <small class="text-muted">Configure cores, imagens e domínio</small>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger py-2">
            <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST"
          action="{{ $nicho->exists ? route('super.admin.nichos.update', $nicho) : route('super.admin.nichos.store') }}"
          enctype="multipart/form-data">
        @csrf
        @if($nicho->exists) @method('PUT') @endif

        <div class="row g-4">

            {{-- Coluna esquerda --}}
            <div class="col-md-7">

                {{-- Identificação --}}
                <div class="form-card mb-4">
                    <div class="section-title">Identificação</div>
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label class="form-label fw-semibold">Slug do nicho <span class="text-danger">*</span></label>
                            <input type="text" name="nicho" class="form-control"
                                   placeholder="surf, pilates, boxe..."
                                   value="{{ old('nicho', $nicho->nicho) }}"
                                   {{ $nicho->exists ? 'readonly' : '' }}>
                            <small class="text-muted">Identificador único. Não pode ser alterado depois.</small>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label fw-semibold">Nome do sistema <span class="text-danger">*</span></label>
                            <input type="text" name="nome" class="form-control"
                                   placeholder="Surf Gestão, Pilates Gestão..."
                                   value="{{ old('nome', $nicho->nome) }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Emoji</label>
                            <input type="text" name="emoji" class="form-control text-center"
                                   placeholder="🏄" maxlength="4"
                                   value="{{ old('emoji', $nicho->emoji) }}"
                                   style="font-size:1.4rem">
                        </div>
                    </div>
                </div>

                {{-- Domínios --}}
                <div class="form-card mb-4">
                    <div class="section-title">Domínios</div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Domínio principal</label>
                            <input type="text" name="dominio" class="form-control"
                                   placeholder="surfgestao.com.br"
                                   value="{{ old('dominio', $nicho->dominio) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Domínio www</label>
                            <input type="text" name="dominio_www" class="form-control"
                                   placeholder="www.surfgestao.com.br"
                                   value="{{ old('dominio_www', $nicho->dominio_www) }}">
                        </div>
                    </div>
                    <small class="text-muted mt-2 d-block">
                        Quando o usuário acessar esses domínios, as configurações deste nicho serão aplicadas automaticamente.
                    </small>
                </div>

                {{-- Cores --}}
                <div class="form-card mb-4">
                    <div class="section-title">Identidade visual</div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Cor primária</label>
                            <div class="color-input-wrap">
                                <input type="color" name="cor_primaria" id="corA"
                                       value="{{ old('cor_primaria', $nicho->cor_primaria ?? '#0e86c8') }}">
                                <input type="text" class="form-control form-control-sm" id="corAText"
                                       value="{{ old('cor_primaria', $nicho->cor_primaria ?? '#0e86c8') }}"
                                       maxlength="7" style="font-family:monospace">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Cor secundária</label>
                            <div class="color-input-wrap">
                                <input type="color" name="cor_secundaria" id="corB"
                                       value="{{ old('cor_secundaria', $nicho->cor_secundaria ?? '#0a5c8a') }}">
                                <input type="text" class="form-control form-control-sm" id="corBText"
                                       value="{{ old('cor_secundaria', $nicho->cor_secundaria ?? '#0a5c8a') }}"
                                       maxlength="7" style="font-family:monospace">
                            </div>
                        </div>
                    </div>
                    <div class="preview-gradient" id="gradientPreview"
                         style="background: linear-gradient(135deg, {{ old('cor_primaria', $nicho->cor_primaria ?? '#0e86c8') }}, {{ old('cor_secundaria', $nicho->cor_secundaria ?? '#0a5c8a') }})">
                    </div>
                    <small class="text-muted mt-1 d-block">Preview do gradiente de login</small>
                </div>

                {{-- Status --}}
                <div class="form-card">
                    <div class="section-title">Status</div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="ativo" id="ativo" value="1"
                               {{ old('ativo', $nicho->ativo ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="ativo">Nicho ativo</label>
                    </div>
                </div>

            </div>

            {{-- Coluna direita — imagens --}}
            <div class="col-md-5">

                {{-- Logo --}}
                <div class="form-card mb-4">
                    <div class="section-title">Logo</div>
                    <label class="upload-area d-block" for="logo">
                        @if($nicho->logo)
                            <img src="{{ $nicho->logo_url }}" alt="Logo atual" id="previewLogo">
                            <div class="mt-2 text-muted small">Clique para trocar</div>
                        @else
                            <i class="fas fa-image fa-2x text-muted mb-2 d-block"></i>
                            <span class="text-muted small">Clique para enviar a logo<br>PNG ou SVG recomendado</span>
                        @endif
                    </label>
                    <input type="file" name="logo" id="logo" accept="image/*" class="d-none"
                           onchange="previewImg(this, 'previewLogo', 'logo')">
                </div>

                {{-- Imagem de login --}}
                <div class="form-card mb-4">
                    <div class="section-title">Imagem de Login</div>
                    <label class="upload-area d-block" for="login_imagem">
                        @if($nicho->login_imagem)
                            <img src="{{ $nicho->login_imagem_url }}" alt="Login" id="previewLogin"
                                 style="max-height:120px;object-fit:cover;border-radius:8px;width:100%">
                            <div class="mt-2 text-muted small">Clique para trocar</div>
                        @else
                            <i class="fas fa-sign-in-alt fa-2x text-muted mb-2 d-block"></i>
                            <span class="text-muted small">Aparece no lado esquerdo da tela de login<br>Recomendado: 600×800px</span>
                        @endif
                    </label>
                    <input type="file" name="login_imagem" id="login_imagem" accept="image/*" class="d-none"
                           onchange="previewImg(this, 'previewLogin', 'login_imagem')">
                </div>

                {{-- Imagem de registro --}}
                <div class="form-card">
                    <div class="section-title">Imagem de Registro</div>
                    <label class="upload-area d-block" for="registro_imagem">
                        @if($nicho->registro_imagem)
                            <img src="{{ $nicho->registro_imagem_url }}" alt="Registro" id="previewRegistro"
                                 style="max-height:120px;object-fit:cover;border-radius:8px;width:100%">
                            <div class="mt-2 text-muted small">Clique para trocar</div>
                        @else
                            <i class="fas fa-user-plus fa-2x text-muted mb-2 d-block"></i>
                            <span class="text-muted small">Aparece na tela de registro<br>Recomendado: 600×800px</span>
                        @endif
                    </label>
                    <input type="file" name="registro_imagem" id="registro_imagem" accept="image/*" class="d-none"
                           onchange="previewImg(this, 'previewRegistro', 'registro_imagem')">
                </div>

            </div>
        </div>

        <div class="d-flex gap-3 mt-4">
            <button type="submit" class="btn btn-primary px-4">
                <i class="fas fa-save me-2"></i> {{ $nicho->exists ? 'Salvar alterações' : 'Criar nicho' }}
            </button>
            <a href="{{ route('super.admin.nichos') }}" class="btn btn-outline-secondary">Cancelar</a>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Sincroniza color picker com input de texto e atualiza preview
function syncColor(pickerId, textId) {
    const picker = document.getElementById(pickerId);
    const text   = document.getElementById(textId);
    const update = () => {
        picker.value = text.value;
        text.value   = picker.value;
        updateGradient();
    };
    picker.addEventListener('input', update);
    text.addEventListener('change', update);
}

function updateGradient() {
    const a = document.getElementById('corA').value;
    const b = document.getElementById('corB').value;
    document.getElementById('gradientPreview').style.background =
        `linear-gradient(135deg, ${a}, ${b})`;
}

syncColor('corA', 'corAText');
syncColor('corB', 'corBText');

// Preview das imagens antes de salvar
function previewImg(input, previewId, fieldId) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = e => {
        let img = document.getElementById(previewId);
        if (!img) {
            img = document.createElement('img');
            img.id = previewId;
            img.style.cssText = 'max-height:120px;object-fit:cover;border-radius:8px;width:100%';
            input.closest('label').innerHTML = '';
            input.closest('label').appendChild(img);
        }
        img.src = e.target.result;
    };
    reader.readAsDataURL(input.files[0]);
}
</script>
</body>
</html>
