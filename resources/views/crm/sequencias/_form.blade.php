{{-- $prefixo: 'nova' ou 'editar' --}}

<div class="row g-3">
    {{-- Nome --}}
    <div class="col-md-8">
        <label class="form-label fw-semibold">Nome da Sequência <span class="text-danger">*</span></label>
        <input type="text" name="nome" class="form-control" required placeholder="Ex: Boas-vindas Pilates — 3 dias">
    </div>

    {{-- Ativo (só no form de edição) --}}
    @if($prefixo === 'editar')
    <div class="col-md-4 d-flex align-items-end">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="ativo" id="ativo-editar" value="1" checked>
            <label class="form-check-label" for="ativo-editar">Sequência Ativa</label>
        </div>
    </div>
    @endif

    {{-- Descrição --}}
    <div class="col-12">
        <label class="form-label">Descrição <small class="text-muted">(opcional)</small></label>
        <input type="text" name="descricao" class="form-control" placeholder="Descreva o objetivo desta sequência">
    </div>

    {{-- Gatilho --}}
    <div class="col-md-5">
        <label class="form-label fw-semibold">⚡ Quando disparar? <span class="text-danger">*</span></label>
        <select name="gatilho" class="form-select" required onchange="updateGatilhoValor('{{ $prefixo }}', this.value, null)">
            <option value="manual">🖱 Manual (via botão no lead)</option>
            <option value="pipeline_status">📋 Ao mover lead para status do pipeline</option>
            <option value="novo_lead">🆕 Ao criar novo lead</option>
        </select>
    </div>

    {{-- Valor do gatilho (pipeline_status) --}}
    <div class="col-md-4 d-none" id="gatilho-valor-wrapper-{{ $prefixo }}">
        <label class="form-label fw-semibold">Status do Pipeline</label>
        <select name="gatilho_valor" class="form-select">
            <option value="">Selecione...</option>
            @foreach(\App\Models\Lead::$pipelineStatus as $val => $label)
                <option value="{{ $val }}">{{ $label }}</option>
            @endforeach
        </select>
    </div>
</div>

<hr class="my-4">

<div class="d-flex justify-content-between align-items-center mb-3">
    <h6 class="mb-0 fw-semibold">📨 Etapas da Sequência</h6>
    <button type="button" class="btn btn-sm btn-outline-primary" onclick="adicionarEtapa('{{ $prefixo }}')">
        + Adicionar Etapa
    </button>
</div>

<div id="etapas-container-{{ $prefixo }}">
    {{-- Etapas inseridas dinamicamente por JS --}}
</div>

<div class="alert alert-light border mt-3 p-3" style="font-size:.85rem">
    <strong>Dicas:</strong><br>
    • <strong>Gerada pela IA:</strong> você define a instrução, a IA cria uma mensagem única e personalizada para cada lead.<br>
    • <strong>Template fixo:</strong> escreva o texto com variáveis: <code>{nome}</code>, <code>{primeiro_nome}</code>, <code>{interesse}</code>, <code>{bairro}</code>.<br>
    • <strong>Delay 0 dias / 0 horas</strong> = envio imediato ao disparar a sequência.<br>
    • Se o lead não tiver telefone, etapas de WhatsApp são puladas. Se não tiver e-mail, etapas de e-mail são puladas.
</div>
