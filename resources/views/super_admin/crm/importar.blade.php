<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin — Importar Leads</title>
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
        .main { margin-left: 240px; padding: 32px; max-width: 860px; }
        .mode-tab { cursor: pointer; padding: 10px 20px; border-radius: 8px; font-weight: 600; font-size: .88rem; border: 2px solid transparent; color: #6b7280; transition: all .2s; }
        .mode-tab.active { border-color: #3b82f6; background: #eff6ff; color: #1d4ed8; }
        .exemplo-badge { display: inline-block; background: #f1f5f9; border-radius: 6px; padding: 2px 8px; font-size: .78rem; color: #475569; font-family: monospace; }
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
        <a href="{{ route('super.admin.nichos') }}"   class="nav-link"><i class="fas fa-palette"></i> Nichos</a>
        <a href="{{ route('super.admin.videos') }}"   class="nav-link"><i class="fas fa-play-circle"></i> Vídeos</a>
        <a href="{{ route('super.admin.conteudos') }}" class="nav-link"><i class="fas fa-pen-nib"></i> Conteúdo / IA</a>
        <div class="nav-section">CRM</div>
        <a href="{{ route('super.admin.crm.leads') }}"      class="nav-link active"><i class="fas fa-users"></i> Leads</a>
        <a href="{{ route('super.admin.crm.pipeline') }}"   class="nav-link"><i class="fas fa-columns"></i> Pipeline</a>
        <a href="{{ route('super.admin.crm.sequencias') }}" class="nav-link"><i class="fas fa-robot"></i> Sequências</a>
        <a href="{{ route('super.admin.crm.templates') }}"  class="nav-link"><i class="fas fa-envelope-open-text"></i> Templates</a>
        <a href="{{ route('home') }}" class="nav-link mt-3"><i class="fas fa-arrow-left"></i> Voltar</a>
    </nav>
</div>

<div class="main">

    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('super.admin.crm.leads') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Voltar
        </a>
        <div>
            <h4 class="mb-0 fw-bold">Importar Leads</h4>
            <small class="text-muted">Cole uma lista ou envie um arquivo CSV</small>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger py-2">
            <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('super.admin.crm.importar.store') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="modo" id="modoInput" value="lista">

        <div class="card border-0 shadow-sm p-4 mb-4">

            {{-- Abas de modo --}}
            <div class="d-flex gap-2 mb-4">
                <div class="mode-tab active" id="tabLista" onclick="setModo('lista')">
                    <i class="fas fa-list me-1"></i> Colar lista
                </div>
                <div class="mode-tab" id="tabCsv" onclick="setModo('csv')">
                    <i class="fas fa-file-csv me-1"></i> Upload CSV
                </div>
            </div>

            {{-- Configurações comuns --}}
            <div class="row g-3 mb-4">
                <div class="col-md-5">
                    <label class="form-label fw-semibold">Nicho <span class="text-danger">*</span></label>
                    <select name="nicho" class="form-select" required>
                        <option value="">Selecione...</option>
                        <option value="surf"     @selected(old('nicho') === 'surf')>🏄 Surf</option>
                        <option value="pilates"  @selected(old('nicho') === 'pilates')>🧘 Pilates</option>
                        <option value="natacao"  @selected(old('nicho') === 'natacao')>🏊 Natação</option>
                        <option value="academia" @selected(old('nicho') === 'academia')>💪 Academia</option>
                        <option value="esportes" @selected(old('nicho') === 'esportes')>⚽ Esportes</option>
                        <option value="landing"  @selected(old('nicho') === 'landing')>🌐 Outros</option>
                    </select>
                </div>
                <div class="col-md-5">
                    <label class="form-label fw-semibold">Cidade / Região <small class="text-muted fw-normal">(opcional)</small></label>
                    <input type="text" name="cidade" class="form-control" placeholder="Ex: Búzios, RJ"
                           value="{{ old('cidade') }}">
                    <div class="form-text">Será salvo no campo "bairro" para identificar a origem geográfica.</div>
                </div>
            </div>

            {{-- Modo: Lista colada --}}
            <div id="secaoLista">
                <label class="form-label fw-semibold">Lista de contatos</label>
                <div class="form-text mb-2">
                    Um contato por linha. Formatos aceitos:<br>
                    <span class="exemplo-badge">Nome;Telefone</span>
                    <span class="exemplo-badge">Nome,Telefone</span>
                    <span class="exemplo-badge">Nome - Telefone</span>
                    <span class="exemplo-badge">Nome;Telefone;Email</span>
                    <span class="exemplo-badge">Nome;Telefone;Nome da Escola</span>
                </div>
                <textarea name="lista" id="textoLista" class="form-control font-monospace" rows="14"
                          placeholder="Escola de Surf Búzios;(22) 99999-0001&#10;Surf Point;22988880002&#10;Blue Wave Surf School;22977770003;bluewavesurf@gmail.com&#10;Escola Ondas do Mar - (22) 96666-0004">{{ old('lista') }}</textarea>
                <div class="form-text mt-1">
                    <i class="fas fa-info-circle text-primary me-1"></i>
                    Duplicatas por telefone são ignoradas automaticamente.
                </div>
            </div>

            {{-- Modo: CSV --}}
            <div id="secaoCsv" style="display:none">
                <label class="form-label fw-semibold">Arquivo CSV</label>
                <div class="form-text mb-2">
                    Colunas esperadas (separadas por <code>;</code>):
                    <span class="exemplo-badge">nome</span>
                    <span class="exemplo-badge">telefone</span>
                    <span class="exemplo-badge">email (opcional)</span>
                    <span class="exemplo-badge">empresa (opcional)</span>
                    <br>A primeira linha pode ser cabeçalho — será detectada automaticamente.
                </div>
                <input type="file" name="csv" class="form-control" accept=".csv,.txt">
                <div class="form-text mt-1">
                    <i class="fas fa-download text-muted me-1"></i>
                    <a href="data:text/csv;charset=utf-8,nome;telefone;email;empresa%0AEscola Surf Búzios;22999990001;surf@buzios.com;Escola Surf Búzios" download="modelo_importacao.csv">
                        Baixar modelo CSV
                    </a>
                </div>
            </div>

        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary px-4">
                <i class="fas fa-file-import me-2"></i> Importar Leads
            </button>
            <a href="{{ route('super.admin.crm.leads') }}" class="btn btn-outline-secondary">Cancelar</a>
        </div>

    </form>

    {{-- Preview em tempo real --}}
    <div id="previewBox" class="card border-0 shadow-sm mt-4" style="display:none">
        <div class="card-header bg-white fw-semibold py-2 px-4">
            <i class="fas fa-eye me-2 text-primary"></i>
            Preview — <span id="previewQtd">0</span> contato(s) detectado(s)
        </div>
        <div class="table-responsive" style="max-height:260px;overflow-y:auto">
            <table class="table table-sm mb-0 align-middle">
                <thead class="table-light sticky-top">
                    <tr>
                        <th>#</th>
                        <th>Nome</th>
                        <th>Telefone</th>
                        <th>E-mail / Empresa</th>
                    </tr>
                </thead>
                <tbody id="previewBody"></tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function setModo(modo) {
    document.getElementById('modoInput').value = modo;
    document.getElementById('secaoLista').style.display = modo === 'lista' ? '' : 'none';
    document.getElementById('secaoCsv').style.display  = modo === 'csv'   ? '' : 'none';
    document.getElementById('tabLista').classList.toggle('active', modo === 'lista');
    document.getElementById('tabCsv').classList.toggle('active',  modo === 'csv');
    document.getElementById('previewBox').style.display = modo === 'lista' ? '' : 'none';
}

// Preview em tempo real
const textarea = document.getElementById('textoLista');
const previewBox  = document.getElementById('previewBox');
const previewQtd  = document.getElementById('previewQtd');
const previewBody = document.getElementById('previewBody');

function parseLinha(linha) {
    linha = linha.trim();
    if (!linha) return null;
    const partes = linha.split(/[;|]|(?<!\d),(?!\d)| – | - /).map(s => s.trim());
    return {
        nome:     partes[0] || '',
        telefone: partes[1] ? partes[1].replace(/\D/g, '') : '',
        extra:    partes[2] || '',
    };
}

function atualizarPreview() {
    const linhas = textarea.value.split('\n').filter(l => l.trim().length > 2);
    const dados  = linhas.map(parseLinha).filter(Boolean);

    previewQtd.textContent = dados.length;
    previewBox.style.display = dados.length > 0 ? '' : 'none';

    previewBody.innerHTML = dados.slice(0, 50).map((d, i) => `
        <tr>
            <td class="text-muted ps-3">${i + 1}</td>
            <td class="fw-semibold">${escHtml(d.nome)}</td>
            <td>${d.telefone ? '<i class="fab fa-whatsapp text-success me-1"></i>' + escHtml(d.telefone) : '<span class="text-muted">—</span>'}</td>
            <td><small class="text-muted">${escHtml(d.extra)}</small></td>
        </tr>
    `).join('');

    if (dados.length > 50) {
        previewBody.innerHTML += `<tr><td colspan="4" class="text-center text-muted py-2">… e mais ${dados.length - 50} contato(s)</td></tr>`;
    }
}

function escHtml(str) {
    return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}

textarea.addEventListener('input', atualizarPreview);

// Inicia com os dados do old()
if (textarea.value.trim()) atualizarPreview();
</script>
</body>
</html>
