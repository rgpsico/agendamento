<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin — Templates de Email</title>
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
        .card-template { background: #fff; border-radius: 12px; border: 1px solid #e9ecf0; padding: 20px; }
        .var-badge { background: #ede9fe; color: #5b21b6; font-size: .72rem; padding: 2px 7px; border-radius: 4px; font-family: monospace; }
        /* Painel IA */
        .ia-panel { background: #0f172a; border-radius: 12px; padding: 20px; color: #e2e8f0; }
        .ia-panel textarea { background: #1e293b; border: 1px solid #334155; color: #e2e8f0; resize: none; }
        .ia-panel textarea::placeholder { color: #64748b; }
        .ia-panel textarea:focus { background: #1e293b; border-color: #6366f1; color: #e2e8f0; box-shadow: none; outline: none; }
        .ia-panel select { background: #1e293b; border: 1px solid #334155; color: #e2e8f0; }
        .ia-panel label { color: #94a3b8; font-size: .82rem; }
        .pulse { display: inline-flex; gap: 4px; align-items: center; }
        .pulse span { width: 7px; height: 7px; border-radius: 50%; background: #818cf8; animation: blink 1.2s infinite; }
        .pulse span:nth-child(2) { animation-delay: .2s; }
        .pulse span:nth-child(3) { animation-delay: .4s; }
        @keyframes blink { 0%,100%{opacity:.2} 50%{opacity:1} }
    </style>
</head>
<body>
<div class="sidebar">
    <div class="logo"><i class="fas fa-layer-group me-2"></i> Super Admin<small>{{ auth()->user()->email }}</small></div>
    <nav class="mt-2">
        <a href="{{ route('super.admin.index') }}"    class="nav-link"><i class="fas fa-chart-pie"></i> Dashboard</a>
        <a href="{{ route('super.admin.clientes') }}" class="nav-link"><i class="fas fa-building"></i> Clientes</a>
        <a href="{{ route('super.admin.nichos') }}"   class="nav-link"><i class="fas fa-palette"></i> Nichos</a>
        <a href="{{ route('super.admin.videos') }}"   class="nav-link"><i class="fas fa-play-circle"></i> Vídeos</a>
        <a href="{{ route('super.admin.conteudos') }}" class="nav-link"><i class="fas fa-pen-nib"></i> Conteúdo / IA</a>
        <div class="nav-section">CRM</div>
        <a href="{{ route('super.admin.crm.leads') }}"      class="nav-link"><i class="fas fa-users"></i> Leads</a>
        <a href="{{ route('super.admin.crm.pipeline') }}"   class="nav-link"><i class="fas fa-columns"></i> Pipeline</a>
        <a href="{{ route('super.admin.crm.sequencias') }}" class="nav-link"><i class="fas fa-robot"></i> Sequências</a>
        <a href="{{ route('super.admin.crm.templates') }}"  class="nav-link active"><i class="fas fa-envelope-open-text"></i> Templates</a>
        <a href="{{ route('home') }}" class="nav-link mt-3"><i class="fas fa-arrow-left"></i> Voltar</a>
    </nav>
</div>

<div class="main">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0 fw-bold">Templates de Email</h4>
            <small class="text-muted">Templates para automações do CRM do super admin</small>
        </div>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalNovoTemplate">
            <i class="fas fa-plus me-1"></i> Novo template
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success py-2">{{ session('success') }}</div>
    @endif

    {{-- Filtro por nicho --}}
    <div class="d-flex gap-2 mb-4 flex-wrap">
        <a href="{{ route('super.admin.crm.templates') }}"
           class="btn btn-sm {{ !$nicho ? 'btn-dark' : 'btn-outline-secondary' }}">Todos</a>
        @foreach($nichos as $n)
        <a href="{{ route('super.admin.crm.templates', ['nicho' => $n->nicho]) }}"
           class="btn btn-sm {{ $nicho === $n->nicho ? 'btn-dark' : 'btn-outline-secondary' }}">
            {{ $n->emoji ?? '' }} {{ $n->nome }}
        </a>
        @endforeach
    </div>

    {{-- Variáveis disponíveis --}}
    <div class="alert alert-light border mb-4" style="font-size:.83rem">
        <strong>Variáveis disponíveis:</strong>
        <span class="var-badge">{nome}</span>
        <span class="var-badge">{email}</span>
        <span class="var-badge">{telefone}</span>
        <span class="var-badge">{empresa}</span>
        <span class="var-badge">{interesse}</span>
    </div>

    <div class="row g-3">
        @forelse($templates as $tpl)
        <div class="col-md-6">
            <div class="card-template h-100">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <div class="fw-bold">{{ $tpl->nome }}</div>
                        <small class="text-muted">
                            @if($tpl->nicho)
                                <span class="badge bg-primary bg-opacity-10 text-primary">{{ $tpl->nicho }}</span>
                            @else
                                <span class="badge bg-secondary bg-opacity-10 text-secondary">Todos os nichos</span>
                            @endif
                        </small>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-primary"
                                onclick="editarTemplate({{ $tpl->id }}, '{{ addslashes($tpl->nome) }}', '{{ addslashes($tpl->nicho ?? '') }}', '{{ addslashes($tpl->assunto) }}', '{{ addslashes(nl2br($tpl->corpo)) }}', {{ $tpl->ativo ? 'true' : 'false' }})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <form method="POST" action="{{ route('super.admin.crm.templates.destroy', $tpl) }}"
                              onsubmit="return confirm('Remover template?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </div>
                <div class="text-muted" style="font-size:.82rem"><strong>Assunto:</strong> {{ $tpl->assunto }}</div>
                <div class="mt-2 p-2 bg-light rounded" style="font-size:.8rem;max-height:80px;overflow:hidden">
                    {!! nl2br(e(Str::limit($tpl->corpo, 200))) !!}
                </div>
                <div class="mt-2">
                    <span class="badge {{ $tpl->ativo ? 'bg-success' : 'bg-secondary' }}">
                        {{ $tpl->ativo ? 'Ativo' : 'Inativo' }}
                    </span>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center text-muted py-5">
            Nenhum template cadastrado. <a href="#" data-bs-toggle="modal" data-bs-target="#modalNovoTemplate">Criar o primeiro</a>.
        </div>
        @endforelse
    </div>
</div>

{{-- Modal Novo Template --}}
<div class="modal fade" id="modalNovoTemplate" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <form method="POST" action="{{ route('super.admin.crm.templates.store') }}" id="formNovoTemplate">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Novo Template de Email</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-4">

                        {{-- Coluna esquerda: formulário --}}
                        <div class="col-md-7">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Nome do template <span class="text-danger">*</span></label>
                                    <input type="text" name="nome" id="novoNome" class="form-control" placeholder="Ex: Boas-vindas Surf" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Nicho</label>
                                    <select name="nicho" id="novoNicho" class="form-select">
                                        <option value="">Todos os nichos</option>
                                        @foreach($nichos as $n)
                                        <option value="{{ $n->nicho }}">{{ $n->emoji ?? '' }} {{ $n->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Assunto do email <span class="text-danger">*</span></label>
                                    <input type="text" name="assunto" id="novoAssunto" class="form-control"
                                           placeholder="Ex: Olá {nome}, seja bem-vindo!" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Corpo do email <span class="text-danger">*</span></label>
                                    <textarea name="corpo" id="novoCorpo" class="form-control" rows="10"
                                        placeholder="Olá {nome},&#10;&#10;Obrigado pelo seu interesse...&#10;&#10;Use {nome}, {email}, {telefone} para personalizar." required></textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Coluna direita: painel IA --}}
                        <div class="col-md-5">
                            <div class="ia-panel h-100">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <span style="font-size:1.2rem">🤖</span>
                                    <span class="fw-bold" style="color:#a5b4fc">Gerar com DeepSeek</span>
                                </div>
                                <p style="font-size:.82rem;color:#94a3b8;margin-bottom:16px">
                                    Descreva o objetivo do email e a IA cria o assunto e corpo automaticamente.
                                </p>

                                <div class="mb-3">
                                    <label>Tom da mensagem</label>
                                    <select id="iaTom" class="form-select form-select-sm mt-1">
                                        <option value="profissional e amigável">Profissional e amigável</option>
                                        <option value="formal">Formal</option>
                                        <option value="descontraído e motivador">Descontraído e motivador</option>
                                        <option value="urgente e persuasivo">Urgente e persuasivo</option>
                                        <option value="caloroso e pessoal">Caloroso e pessoal</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label>O que você quer no email?</label>
                                    <textarea id="iaInstrucao" class="form-control form-control-sm mt-1" rows="5"
                                        placeholder="Ex: Email de boas-vindas para leads do surf que preencheram o formulário. Apresentar a plataforma, destacar os benefícios e convidar para uma demonstração gratuita."></textarea>
                                </div>

                                <button type="button" id="btnGerarIA" onclick="gerarComIA()"
                                        class="btn btn-sm w-100"
                                        style="background:#6366f1;color:#fff;border:none">
                                    <i class="fas fa-magic me-2"></i> Gerar template
                                </button>

                                {{-- Loading --}}
                                <div id="iaLoading" style="display:none;margin-top:16px">
                                    <div class="d-flex align-items-center gap-2" style="color:#94a3b8;font-size:.83rem">
                                        <div class="pulse"><span></span><span></span><span></span></div>
                                        Gerando com DeepSeek...
                                    </div>
                                </div>

                                {{-- Erro --}}
                                <div id="iaErro" style="display:none;margin-top:12px;background:#450a0a;border-radius:8px;padding:10px;font-size:.82rem;color:#fca5a5"></div>

                                {{-- Sucesso --}}
                                <div id="iaSucesso" style="display:none;margin-top:12px;background:#052e16;border-radius:8px;padding:10px;font-size:.82rem;color:#86efac">
                                    <i class="fas fa-check-circle me-1"></i> Template gerado! Revise os campos ao lado e salve.
                                </div>

                                <div style="margin-top:20px;padding-top:16px;border-top:1px solid #1e293b;font-size:.75rem;color:#475569">
                                    Variáveis: <code style="color:#818cf8">{nome}</code> <code style="color:#818cf8">{email}</code>
                                    <code style="color:#818cf8">{telefone}</code> <code style="color:#818cf8">{empresa}</code>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Salvar template</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Modal Editar Template --}}
<div class="modal fade" id="modalEditarTemplate" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form method="POST" id="formEditarTemplate">
            @csrf @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Template</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nome <span class="text-danger">*</span></label>
                            <input type="text" name="nome" id="editNome" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nicho</label>
                            <select name="nicho" id="editNicho" class="form-select">
                                <option value="">Todos os nichos</option>
                                @foreach($nichos as $n)
                                <option value="{{ $n->nicho }}">{{ $n->emoji ?? '' }} {{ $n->nome }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Assunto <span class="text-danger">*</span></label>
                            <input type="text" name="assunto" id="editAssunto" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Corpo <span class="text-danger">*</span></label>
                            <textarea name="corpo" id="editCorpo" class="form-control" rows="8" required></textarea>
                        </div>
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="ativo" id="editAtivo" value="1">
                                <label class="form-check-label" for="editAtivo">Template ativo</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Salvar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
async function gerarComIA() {
    const instrucao = document.getElementById('iaInstrucao').value.trim();
    if (!instrucao) {
        document.getElementById('iaErro').textContent = 'Descreva o que você quer no email antes de gerar.';
        document.getElementById('iaErro').style.display = 'block';
        return;
    }

    const nicho   = document.getElementById('novoNicho').value;
    const tom     = document.getElementById('iaTom').value;
    const btn     = document.getElementById('btnGerarIA');
    const loading = document.getElementById('iaLoading');
    const erroDiv = document.getElementById('iaErro');
    const okDiv   = document.getElementById('iaSucesso');

    // Estado loading
    btn.disabled  = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Gerando...';
    loading.style.display = 'block';
    erroDiv.style.display = 'none';
    okDiv.style.display   = 'none';

    try {
        const res = await fetch('{{ route('super.admin.crm.templates.gerar-ia') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ instrucao, nicho, tom }),
        });

        const data = await res.json();

        if (!res.ok || data.error) {
            throw new Error(data.error || 'Erro desconhecido');
        }

        // Preenche os campos do formulário
        document.getElementById('novoAssunto').value = data.assunto;
        document.getElementById('novoCorpo').value   = data.corpo;

        // Sugere um nome se estiver vazio
        if (!document.getElementById('novoNome').value) {
            document.getElementById('novoNome').value = 'Template gerado por IA';
        }

        okDiv.style.display = 'block';

        // Animação de destaque nos campos preenchidos
        ['novoAssunto', 'novoCorpo'].forEach(id => {
            const el = document.getElementById(id);
            el.style.transition = 'background .3s';
            el.style.background = '#f0fdf4';
            setTimeout(() => el.style.background = '', 1500);
        });

    } catch (err) {
        erroDiv.textContent  = 'Erro: ' + err.message;
        erroDiv.style.display = 'block';
    } finally {
        btn.disabled  = false;
        btn.innerHTML = '<i class="fas fa-magic me-2"></i> Gerar template';
        loading.style.display = 'none';
    }
}

function editarTemplate(id, nome, nicho, assunto, corpo, ativo) {
    document.getElementById('editNome').value    = nome;
    document.getElementById('editAssunto').value = assunto;
    document.getElementById('editCorpo').value   = corpo.replace(/<br\s*\/?>/gi, '\n');
    document.getElementById('editAtivo').checked = ativo;
    document.getElementById('editNicho').value   = nicho;
    document.getElementById('formEditarTemplate').action = '/super-admin/crm/templates/' + id;
    new bootstrap.Modal(document.getElementById('modalEditarTemplate')).show();
}
</script>
</body>
</html>
