<x-admin.layout title="CRM - Automação com IA">
<div class="page-wrapper">
<div class="content container-fluid">

    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h3 class="page-title">🤖 Automação com IA</h3>
            <ul class="breadcrumb">
                <li class="breadcrumb-item">CRM</li>
                <li class="breadcrumb-item active">Sequências de Mensagens</li>
            </ul>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNovaSequencia">
            + Nova Sequência
        </button>
    </div>

    @include('crm._nav')
    <x-alert-messages />

    {{-- ── Explicação rápida ─────────────────────────────────────────────── --}}
    <div class="alert alert-info d-flex gap-3 align-items-start mb-4">
        <span style="font-size:1.5rem">💡</span>
        <div>
            <strong>Como funciona:</strong> Crie sequências de mensagens que a IA envia automaticamente para seus leads via
            <strong>WhatsApp</strong> ou <strong>E-mail</strong>. Você define quando disparar (ex: ao mover um lead para "Em Contato")
            e cada etapa pode usar <strong>IA para gerar uma mensagem personalizada</strong> ou um template com variáveis
            como <code>{nome}</code>, <code>{interesse}</code>, <code>{bairro}</code>.
        </div>
    </div>

    @if($sequencias->isEmpty())
        <div class="card">
            <div class="card-body text-center py-5 text-muted">
                <div style="font-size:3rem">🤖</div>
                <p class="fs-5 mt-3 mb-2">Nenhuma sequência criada ainda.</p>
                <p>Crie sua primeira sequência de automação para começar a nutrir leads automaticamente.</p>
                <button class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#modalNovaSequencia">
                    Criar primeira sequência
                </button>
            </div>
        </div>
    @else
        <div class="row g-3">
            @foreach($sequencias as $seq)
            <div class="col-12">
                <div class="card {{ $seq->ativo ? '' : 'border-secondary opacity-75' }}">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                            <div>
                                <h5 class="mb-1">
                                    {{ $seq->nome }}
                                    @if($seq->ativo)
                                        <span class="badge bg-success ms-1">Ativa</span>
                                    @else
                                        <span class="badge bg-secondary ms-1">Pausada</span>
                                    @endif
                                </h5>
                                <div class="text-muted small mb-2">
                                    <span class="me-3">⚡ {{ $seq->gatilho_label }}</span>
                                    <span class="me-3">📋 {{ $seq->etapas->count() }} etapa{{ $seq->etapas->count() != 1 ? 's' : '' }}</span>
                                    <span class="me-3 text-success">✓ {{ $seq->enviados_count }} enviados</span>
                                    <span class="text-warning">⏳ {{ $seq->pendentes_count }} pendentes</span>
                                </div>
                                @if($seq->descricao)
                                    <p class="mb-2 text-muted small">{{ $seq->descricao }}</p>
                                @endif

                                {{-- Etapas em linha --}}
                                <div class="d-flex flex-wrap gap-2 mt-2">
                                    @foreach($seq->etapas as $etapa)
                                    <div class="badge bg-light text-dark border d-flex align-items-center gap-1 py-2 px-2" style="font-size:.75rem">
                                        <span>
                                            @if($etapa->canal === 'whatsapp') 📱
                                            @elseif($etapa->canal === 'email') 📧
                                            @else 📱📧
                                            @endif
                                        </span>
                                        <span>
                                            Etapa {{ $etapa->ordem }}
                                            @if($etapa->delay_dias > 0 || $etapa->delay_horas > 0)
                                                — {{ $etapa->delay_texto }}
                                            @else
                                                — Imediato
                                            @endif
                                        </span>
                                        @if($etapa->tipo_mensagem === 'ia')
                                            <span class="badge bg-primary" style="font-size:.65rem">IA</span>
                                        @else
                                            <span class="badge bg-secondary" style="font-size:.65rem">Template</span>
                                        @endif
                                    </div>
                                    @if(! $loop->last)
                                        <span class="text-muted align-self-center">→</span>
                                    @endif
                                    @endforeach
                                </div>
                            </div>

                            <div class="d-flex flex-column gap-2 align-items-end">
                                {{-- Toggle ativo --}}
                                <form method="POST" action="{{ route('crm.sequencias.toggle', $seq) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-sm {{ $seq->ativo ? 'btn-outline-warning' : 'btn-outline-success' }}">
                                        {{ $seq->ativo ? '⏸ Pausar' : '▶ Ativar' }}
                                    </button>
                                </form>

                                {{-- Histórico --}}
                                <a href="{{ route('crm.sequencias.envios', $seq) }}" class="btn btn-sm btn-outline-info">
                                    📋 Histórico
                                </a>

                                {{-- Editar --}}
                                <button class="btn btn-sm btn-outline-secondary"
                                    onclick="abrirEditar({{ $seq->toJson() }})">
                                    ✏️ Editar
                                </button>

                                {{-- Excluir --}}
                                <form method="POST" action="{{ route('crm.sequencias.destroy', $seq) }}"
                                    onsubmit="return confirm('Excluir esta sequência e todos os seus envios pendentes?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">🗑 Excluir</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif

</div>
</div>

{{-- ══════════════════════════════════════════════════════════════════════════ --}}
{{--  MODAL: Nova Sequência                                                     --}}
{{-- ══════════════════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalNovaSequencia" tabindex="-1">
<div class="modal-dialog modal-xl modal-dialog-scrollable">
<div class="modal-content">
    <form method="POST" action="{{ route('crm.sequencias.store') }}" id="formNovaSeq">
    @csrf
    <div class="modal-header">
        <h5 class="modal-title">🤖 Nova Sequência de Automação</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        @include('crm.sequencias._form', ['prefixo' => 'nova'])
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary">Criar Sequência</button>
    </div>
    </form>
</div>
</div>
</div>

{{-- ══════════════════════════════════════════════════════════════════════════ --}}
{{--  MODAL: Editar Sequência                                                   --}}
{{-- ══════════════════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalEditarSequencia" tabindex="-1">
<div class="modal-dialog modal-xl modal-dialog-scrollable">
<div class="modal-content">
    <form method="POST" id="formEditarSeq" action="">
    @csrf @method('PUT')
    <div class="modal-header">
        <h5 class="modal-title">✏️ Editar Sequência</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        @include('crm.sequencias._form', ['prefixo' => 'editar'])
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
    </div>
    </form>
</div>
</div>
</div>

<script>
// ── Construtor de etapas dinâmico ─────────────────────────────────────────────

let etapaCountNova   = 0;
let etapaCountEditar = 0;

function adicionarEtapa(prefixo) {
    const container = document.getElementById('etapas-container-' + prefixo);
    const idx = (prefixo === 'nova') ? etapaCountNova++ : etapaCountEditar++;

    const html = `
    <div class="card border mb-3 etapa-card" data-idx="${idx}">
        <div class="card-header d-flex justify-content-between align-items-center py-2">
            <strong class="etapa-titulo">Etapa ${idx + 1}</strong>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removerEtapa(this)">Remover</button>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Canal</label>
                    <select name="etapas[${idx}][canal]" class="form-select" required>
                        <option value="whatsapp">📱 WhatsApp</option>
                        <option value="email">📧 E-mail</option>
                        <option value="ambos">📱📧 WhatsApp + E-mail</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tipo de Mensagem</label>
                    <select name="etapas[${idx}][tipo_mensagem]" class="form-select" onchange="toggleTipoMensagem(this, ${idx}, '${prefixo}')">
                        <option value="ia">🤖 Gerada pela IA</option>
                        <option value="template">📝 Template fixo</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Delay (dias)</label>
                    <input type="number" name="etapas[${idx}][delay_dias]" class="form-control" value="0" min="0" max="365">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Delay (horas)</label>
                    <input type="number" name="etapas[${idx}][delay_horas]" class="form-control" value="0" min="0" max="23">
                </div>

                {{-- Campos IA --}}
                <div class="col-12 campo-ia-${prefixo}-${idx}">
                    <label class="form-label">
                        🤖 Instrução para a IA
                        <small class="text-muted">(descreva o que a mensagem deve abordar)</small>
                    </label>
                    <textarea name="etapas[${idx}][instrucao_ia]" class="form-control" rows="2"
                        placeholder="Ex: Ofereça uma aula experimental gratuita de pilates. Mencione os benefícios para a coluna e qualidade de vida. Seja breve e natural."></textarea>
                </div>

                {{-- Campos Template --}}
                <div class="col-12 campo-template-${prefixo}-${idx} d-none">
                    <label class="form-label">
                        📝 Mensagem Template
                        <small class="text-muted">Variáveis: {nome}, {primeiro_nome}, {interesse}, {bairro}, {empresa}</small>
                    </label>
                    <textarea name="etapas[${idx}][template_mensagem]" class="form-control" rows="3"
                        placeholder="Olá {primeiro_nome}! Vi que você tem interesse em {interesse} no bairro {bairro}..."></textarea>
                </div>

                {{-- Assunto e-mail --}}
                <div class="col-12">
                    <label class="form-label">Assunto do E-mail <small class="text-muted">(deixe em branco para usar o padrão)</small></label>
                    <input type="text" name="etapas[${idx}][assunto_email]" class="form-control"
                        placeholder="Ex: Uma oportunidade especial para você, {primeiro_nome}">
                </div>
            </div>
        </div>
    </div>`;

    container.insertAdjacentHTML('beforeend', html);
    renumerarEtapas(prefixo);
}

function removerEtapa(btn) {
    btn.closest('.etapa-card').remove();
    renumerarEtapas('nova');
    renumerarEtapas('editar');
}

function toggleTipoMensagem(sel, idx, prefixo) {
    const isIA = sel.value === 'ia';
    document.querySelector(`.campo-ia-${prefixo}-${idx}`)?.classList.toggle('d-none', !isIA);
    document.querySelector(`.campo-template-${prefixo}-${idx}`)?.classList.toggle('d-none', isIA);
}

function renumerarEtapas(prefixo) {
    document.querySelectorAll('#etapas-container-' + prefixo + ' .etapa-titulo').forEach((el, i) => {
        el.textContent = 'Etapa ' + (i + 1);
    });
}

// ── Abrir modal de edição ──────────────────────────────────────────────────────

function abrirEditar(seq) {
    const form = document.getElementById('formEditarSeq');
    form.action = `/crm/sequencias/${seq.id}`;

    form.querySelector('[name="nome"]').value         = seq.nome ?? '';
    form.querySelector('[name="descricao"]').value    = seq.descricao ?? '';
    form.querySelector('[name="gatilho"]').value      = seq.gatilho ?? 'manual';
    form.querySelector('[name="ativo"]').checked      = seq.ativo;
    updateGatilhoValor('editar', seq.gatilho, seq.gatilho_valor);

    // Limpa etapas antigas
    const container = document.getElementById('etapas-container-editar');
    container.innerHTML = '';
    etapaCountEditar = 0;

    // Dispara gatilho change para mostrar/esconder gatilho_valor
    form.querySelector('[name="gatilho"]').dispatchEvent(new Event('change'));

    // Aguarda renderização e preenche as etapas
    setTimeout(() => {
        (seq.etapas || []).forEach(etapa => {
            adicionarEtapa('editar');
            const idx = etapaCountEditar - 1;

            const c = document.getElementById('etapas-container-editar');
            const card = c.querySelectorAll('.etapa-card')[idx];

            card.querySelector(`[name="etapas[${idx}][canal]"]`).value         = etapa.canal;
            card.querySelector(`[name="etapas[${idx}][tipo_mensagem]"]`).value  = etapa.tipo_mensagem;
            card.querySelector(`[name="etapas[${idx}][delay_dias]"]`).value     = etapa.delay_dias;
            card.querySelector(`[name="etapas[${idx}][delay_horas]"]`).value    = etapa.delay_horas;
            card.querySelector(`[name="etapas[${idx}][instrucao_ia]"]`).value   = etapa.instrucao_ia ?? '';
            card.querySelector(`[name="etapas[${idx}][template_mensagem]"]`).value = etapa.template_mensagem ?? '';
            card.querySelector(`[name="etapas[${idx}][assunto_email]"]`).value  = etapa.assunto_email ?? '';

            // Toggle campos IA/template
            const sel = card.querySelector(`[name="etapas[${idx}][tipo_mensagem]"]`);
            toggleTipoMensagem(sel, idx, 'editar');
        });
    }, 50);

    new bootstrap.Modal(document.getElementById('modalEditarSequencia')).show();
}

// ── Gatilho condicional (mostrar/esconder campo gatilho_valor) ────────────────

function updateGatilhoValor(prefixo, gatilho, valorAtual) {
    const wrapper = document.getElementById('gatilho-valor-wrapper-' + prefixo);
    if (! wrapper) return;

    if (gatilho === 'pipeline_status') {
        wrapper.classList.remove('d-none');
        wrapper.querySelector('select').value = valorAtual ?? '';
    } else {
        wrapper.classList.add('d-none');
    }
}

document.querySelectorAll('[name="gatilho"]').forEach(sel => {
    sel.addEventListener('change', function () {
        const prefixo = this.closest('form').id.includes('nova') ? 'nova' : 'editar';
        updateGatilhoValor(prefixo, this.value, null);
    });
});

// Adiciona 1ª etapa automaticamente ao abrir modal de nova sequência
document.getElementById('modalNovaSequencia').addEventListener('show.bs.modal', function () {
    const container = document.getElementById('etapas-container-nova');
    container.innerHTML = '';
    etapaCountNova = 0;
    adicionarEtapa('nova');
});
</script>

</x-admin.layout>
