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
    </style>
</head>
<body>
<div class="sidebar">
    <div class="logo"><i class="fas fa-layer-group me-2"></i> Super Admin<small>{{ auth()->user()->email }}</small></div>
    <nav class="mt-2">
        <a href="{{ route('super.admin.index') }}"    class="nav-link"><i class="fas fa-chart-pie"></i> Dashboard</a>
        <a href="{{ route('super.admin.clientes') }}" class="nav-link"><i class="fas fa-building"></i> Clientes</a>
        <a href="{{ route('super.admin.nichos') }}"   class="nav-link"><i class="fas fa-palette"></i> Nichos</a>
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
    <div class="modal-dialog modal-lg">
        <form method="POST" action="{{ route('super.admin.crm.templates.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Novo Template de Email</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nome do template <span class="text-danger">*</span></label>
                            <input type="text" name="nome" class="form-control" placeholder="Ex: Boas-vindas Surf" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nicho</label>
                            <select name="nicho" class="form-select">
                                <option value="">Todos os nichos</option>
                                @foreach($nichos as $n)
                                <option value="{{ $n->nicho }}">{{ $n->emoji ?? '' }} {{ $n->nome }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Assunto do email <span class="text-danger">*</span></label>
                            <input type="text" name="assunto" class="form-control" placeholder="Ex: Olá {nome}, seja bem-vindo!" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Corpo do email <span class="text-danger">*</span></label>
                            <textarea name="corpo" class="form-control" rows="8"
                                placeholder="Olá {nome},&#10;&#10;Obrigado pelo seu interesse em nossa plataforma!&#10;..." required></textarea>
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
