<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin — Sequências de Automação</title>
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
        .seq-card { background: #fff; border-radius: 12px; border: 1px solid #e9ecf0; padding: 20px; }
        .seq-card .etapa-dot { width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: .75rem; font-weight: 700; color: #fff; }
        .etapa-email { background: #6366f1; }
        .etapa-whatsapp { background: #22c55e; }
        .etapa-ambos { background: #f59e0b; }
        .connector { width: 2px; height: 20px; background: #e2e8f0; margin: 2px auto; }
        .gatilho-badge { font-size: .72rem; padding: 3px 10px; border-radius: 20px; }
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
        <div class="nav-section">CRM</div>
        <a href="{{ route('super.admin.crm.leads') }}"      class="nav-link"><i class="fas fa-users"></i> Leads</a>
        <a href="{{ route('super.admin.crm.pipeline') }}"   class="nav-link"><i class="fas fa-columns"></i> Pipeline</a>
        <a href="{{ route('super.admin.crm.sequencias') }}" class="nav-link active"><i class="fas fa-robot"></i> Sequências</a>
        <a href="{{ route('super.admin.crm.templates') }}"  class="nav-link"><i class="fas fa-envelope-open-text"></i> Templates</a>
        <a href="{{ route('home') }}" class="nav-link mt-3"><i class="fas fa-arrow-left"></i> Voltar</a>
    </nav>
</div>

<div class="main">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0 fw-bold">Sequências de Automação</h4>
            <small class="text-muted">Envios automáticos de email/WhatsApp para leads do super admin</small>
        </div>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalNovaSeq">
            <i class="fas fa-plus me-1"></i> Nova sequência
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success py-2">{{ session('success') }}</div>
    @endif

    {{-- Filtro --}}
    <div class="d-flex gap-2 mb-4 flex-wrap">
        <a href="{{ route('super.admin.crm.sequencias') }}"
           class="btn btn-sm {{ !$nicho ? 'btn-dark' : 'btn-outline-secondary' }}">Todos</a>
        @foreach($nichos as $n)
        <a href="{{ route('super.admin.crm.sequencias', ['nicho' => $n->nicho]) }}"
           class="btn btn-sm {{ $nicho === $n->nicho ? 'btn-dark' : 'btn-outline-secondary' }}">
            {{ $n->emoji ?? '' }} {{ $n->nome }}
        </a>
        @endforeach
    </div>

    <div class="row g-3">
        @forelse($sequencias as $seq)
        <div class="col-md-6">
            <div class="seq-card">
                {{-- Header --}}
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <div class="fw-bold">{{ $seq->nome }}</div>
                        @if($seq->descricao)
                            <div class="text-muted" style="font-size:.82rem">{{ $seq->descricao }}</div>
                        @endif
                        <div class="d-flex gap-2 mt-1 flex-wrap">
                            @php
                                $gatilhoCores = ['manual' => 'secondary', 'novo_lead' => 'success', 'pipeline_status' => 'warning'];
                                $cor = $gatilhoCores[$seq->gatilho] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $cor }} bg-opacity-15 text-{{ $cor }} gatilho-badge">
                                {{ $seq->gatilho_label }}
                            </span>
                            @if($seq->nicho)
                                <span class="badge bg-primary bg-opacity-10 text-primary gatilho-badge">{{ $seq->nicho }}</span>
                            @else
                                <span class="badge bg-secondary bg-opacity-10 text-secondary gatilho-badge">Todos nichos</span>
                            @endif
                        </div>
                    </div>
                    <div class="d-flex gap-2 align-items-center">
                        <span class="badge {{ $seq->ativo ? 'bg-success' : 'bg-secondary' }}">
                            {{ $seq->ativo ? 'Ativa' : 'Pausada' }}
                        </span>
                        <form method="POST" action="{{ route('super.admin.crm.sequencias.toggle', $seq) }}">
                            @csrf @method('PATCH')
                            <button class="btn btn-sm btn-outline-secondary" title="{{ $seq->ativo ? 'Pausar' : 'Ativar' }}">
                                <i class="fas fa-{{ $seq->ativo ? 'pause' : 'play' }}"></i>
                            </button>
                        </form>
                        <form method="POST" action="{{ route('super.admin.crm.sequencias.destroy', $seq) }}"
                              onsubmit="return confirm('Remover sequência?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </div>

                {{-- Etapas --}}
                <div class="d-flex flex-column align-items-center mb-3">
                    @foreach($seq->etapas as $i => $etapa)
                        @if($i > 0)<div class="connector"></div>@endif
                        <div class="d-flex align-items-center gap-2 w-100">
                            <div class="etapa-dot etapa-{{ $etapa->canal }}">
                                @if($etapa->canal === 'email') <i class="fas fa-envelope"></i>
                                @elseif($etapa->canal === 'whatsapp') <i class="fab fa-whatsapp"></i>
                                @else <i class="fas fa-share-alt"></i> @endif
                            </div>
                            <div style="font-size:.8rem">
                                <strong>Etapa {{ $etapa->ordem }}</strong> · +{{ $etapa->delay_dias }}d {{ $etapa->delay_horas }}h
                                <span class="text-muted">·
                                    {{ $etapa->tipo_mensagem === 'ia' ? '🤖 IA' : '📝 Template' }}
                                </span>
                                @if($etapa->assunto_email)
                                    <br><span class="text-muted">{{ Str::limit($etapa->assunto_email, 50) }}</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Stats --}}
                <div class="d-flex gap-3" style="font-size:.8rem;color:#8892b0">
                    <span><i class="fas fa-paper-plane me-1"></i>{{ $seq->enviados_count }} enviados</span>
                    <span><i class="fas fa-clock me-1"></i>{{ $seq->pendentes_count }} pendentes</span>
                    <span><i class="fas fa-list me-1"></i>{{ $seq->etapas->count() }} etapa(s)</span>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center text-muted py-5">
            Nenhuma sequência cadastrada.
            <a href="#" data-bs-toggle="modal" data-bs-target="#modalNovaSeq">Criar a primeira</a>.
        </div>
        @endforelse
    </div>
</div>

{{-- Modal Nova Sequência --}}
<div class="modal fade" id="modalNovaSeq" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <form method="POST" action="{{ route('super.admin.crm.sequencias.store') }}" id="formSeq">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nova Sequência de Automação</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Nome <span class="text-danger">*</span></label>
                            <input type="text" name="nome" class="form-control" placeholder="Ex: Boas-vindas Surf" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Nicho</label>
                            <select name="nicho" class="form-select">
                                <option value="">Todos os nichos</option>
                                @foreach($nichos as $n)
                                <option value="{{ $n->nicho }}" {{ $nicho === $n->nicho ? 'selected' : '' }}>
                                    {{ $n->emoji ?? '' }} {{ $n->nome }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Gatilho <span class="text-danger">*</span></label>
                            <select name="gatilho" class="form-select" id="gatilhoSelect" required>
                                <option value="novo_lead">🟢 Novo Lead</option>
                                <option value="pipeline_status">🔄 Mudança de Status</option>
                                <option value="manual">🖐 Manual</option>
                            </select>
                        </div>
                        <div class="col-md-4" id="gatilhoValorWrap" style="display:none">
                            <label class="form-label fw-semibold">Status do pipeline</label>
                            <select name="gatilho_valor" class="form-select">
                                @foreach(App\Models\Lead::$pipelineStatus as $val => $label)
                                <option value="{{ $val }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Descrição</label>
                            <input type="text" name="descricao" class="form-control" placeholder="Opcional...">
                        </div>
                    </div>

                    {{-- Etapas --}}
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <strong>Etapas</strong>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="adicionarEtapa()">
                            <i class="fas fa-plus me-1"></i> Adicionar etapa
                        </button>
                    </div>
                    <div id="etapasContainer"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Criar Sequência</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
let etapaIdx = 0;

const templates = @json($templates);

function adicionarEtapa() {
    const i = etapaIdx++;
    const tplOptions = templates.map(t => `<option value="${t.id}">${t.nome}</option>`).join('');

    const html = `
    <div class="border rounded p-3 mb-3 bg-light" id="etapa_${i}">
        <div class="d-flex justify-content-between mb-2">
            <strong>Etapa ${i + 1}</strong>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="document.getElementById('etapa_${i}').remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="row g-2">
            <div class="col-md-3">
                <label class="form-label small">Canal</label>
                <select name="etapas[${i}][canal]" class="form-select form-select-sm" required>
                    <option value="email">📧 Email</option>
                    <option value="whatsapp">💬 WhatsApp</option>
                    <option value="ambos">📧💬 Ambos</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small">Delay dias</label>
                <input type="number" name="etapas[${i}][delay_dias]" class="form-control form-control-sm" value="0" min="0" max="365" required>
            </div>
            <div class="col-md-2">
                <label class="form-label small">Delay horas</label>
                <input type="number" name="etapas[${i}][delay_horas]" class="form-control form-control-sm" value="1" min="0" max="23" required>
            </div>
            <div class="col-md-3">
                <label class="form-label small">Tipo mensagem</label>
                <select name="etapas[${i}][tipo_mensagem]" class="form-select form-select-sm" onchange="toggleTipoMsg(this, ${i})" required>
                    <option value="template">📝 Template</option>
                    <option value="ia">🤖 IA</option>
                </select>
            </div>
            <div class="col-md-12" id="etapa_${i}_assunto">
                <label class="form-label small">Assunto do email</label>
                <input type="text" name="etapas[${i}][assunto_email]" class="form-control form-control-sm" placeholder="Assunto (apenas email)">
            </div>
            <div class="col-md-12" id="etapa_${i}_template_wrap">
                <label class="form-label small">Mensagem / Template</label>
                <select name="etapas[${i}][template_mensagem]" class="form-select form-select-sm">
                    <option value="">Selecione um template ou escreva abaixo</option>
                    ${tplOptions}
                </select>
                <textarea name="etapas[${i}][template_mensagem_texto]" class="form-control form-control-sm mt-1"
                    rows="3" placeholder="Ou escreva a mensagem diretamente... use {nome}, {email}..."></textarea>
            </div>
            <div class="col-md-12" id="etapa_${i}_ia_wrap" style="display:none">
                <label class="form-label small">Instrução para IA</label>
                <textarea name="etapas[${i}][instrucao_ia]" class="form-control form-control-sm"
                    rows="2" placeholder="Escreva um email de boas-vindas caloroso para {nome} que demonstrou interesse em surf..."></textarea>
            </div>
        </div>
    </div>`;
    document.getElementById('etapasContainer').insertAdjacentHTML('beforeend', html);
}

function toggleTipoMsg(sel, i) {
    const isIA = sel.value === 'ia';
    document.getElementById(`etapa_${i}_template_wrap`).style.display = isIA ? 'none' : '';
    document.getElementById(`etapa_${i}_ia_wrap`).style.display       = isIA ? '' : 'none';
}

document.getElementById('gatilhoSelect').addEventListener('change', function() {
    document.getElementById('gatilhoValorWrap').style.display =
        this.value === 'pipeline_status' ? '' : 'none';
});

// Começa com 1 etapa
adicionarEtapa();
</script>
</body>
</html>
