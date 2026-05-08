<div class="row g-3">

    {{-- ── Identificação ─────────────────────────────────────────────────────── --}}
    <div class="col-12"><h6 class="text-muted fw-semibold mb-0">Identificação</h6><hr class="mt-1 mb-0"></div>

    <div class="col-md-6">
        <label class="form-label">Nome interno <span class="text-danger">*</span></label>
        <input type="text" name="nome" class="form-control" required
            value="{{ old('nome', $modal?->nome) }}" placeholder="Ex: Widget Surf Julho">
    </div>
    <div class="col-md-6">
        <label class="form-label">Título do modal <span class="text-danger">*</span></label>
        <input type="text" name="titulo" class="form-control" required
            value="{{ old('titulo', $modal?->titulo) }}" placeholder="Ex: Concorra a uma aula de surf grátis!">
    </div>
    <div class="col-12">
        <label class="form-label">Descrição</label>
        <textarea name="descricao" class="form-control" rows="2"
            placeholder="Texto opcional abaixo do título">{{ old('descricao', $modal?->descricao) }}</textarea>
    </div>
    <div class="col-md-4">
        <label class="form-label">Texto do botão <span class="text-danger">*</span></label>
        <input type="text" name="botao_texto" class="form-control" required
            value="{{ old('botao_texto', $modal?->botao_texto ?? 'Quero participar!') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Mensagem de sucesso</label>
        <input type="text" name="mensagem_sucesso" class="form-control"
            value="{{ old('mensagem_sucesso', $modal?->mensagem_sucesso ?? 'Recebemos seus dados! Em breve entraremos em contato.') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Origem do lead</label>
        <select name="origem_lead" class="form-select">
            @foreach(\App\Models\Lead::$origens as $key => $label)
                <option value="{{ $key }}" @selected(old('origem_lead', $modal?->origem_lead ?? 'site') === $key)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Campanha (opcional)</label>
        <select name="campanha_id" class="form-select">
            <option value="">Nenhuma</option>
            @foreach($campanhas as $c)
                <option value="{{ $c->id }}" @selected(old('campanha_id', $modal?->campanha_id) == $c->id)>{{ $c->nome }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Campos do formulário</label>
        <div class="d-flex gap-3 mt-1">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="campo_nome" value="1"
                    id="campo_nome_{{ $modal?->id ?? 'novo' }}"
                    @checked(old('campo_nome', $modal?->campo_nome ?? true))>
                <label class="form-check-label" for="campo_nome_{{ $modal?->id ?? 'novo' }}">Nome</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="campo_email" value="1"
                    id="campo_email_{{ $modal?->id ?? 'novo' }}"
                    @checked(old('campo_email', $modal?->campo_email ?? true))>
                <label class="form-check-label" for="campo_email_{{ $modal?->id ?? 'novo' }}">E-mail</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="campo_telefone" value="1"
                    id="campo_telefone_{{ $modal?->id ?? 'novo' }}"
                    @checked(old('campo_telefone', $modal?->campo_telefone ?? true))>
                <label class="form-check-label" for="campo_telefone_{{ $modal?->id ?? 'novo' }}">Telefone</label>
            </div>
        </div>
    </div>

    {{-- ── Estilo ─────────────────────────────────────────────────────────────── --}}
    <div class="col-12 mt-2"><h6 class="text-muted fw-semibold mb-0">Estilo</h6><hr class="mt-1 mb-0"></div>

    <div class="col-md-3">
        <label class="form-label">Tamanho</label>
        <select name="tamanho" class="form-select">
            <option value="pequeno" @selected(old('tamanho', $modal?->tamanho ?? 'medio') === 'pequeno')>Pequeno (320px)</option>
            <option value="medio"   @selected(old('tamanho', $modal?->tamanho ?? 'medio') === 'medio')>Médio (420px)</option>
            <option value="grande"  @selected(old('tamanho', $modal?->tamanho ?? 'medio') === 'grande')>Grande (560px)</option>
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Posição</label>
        <select name="posicao" class="form-select">
            <option value="centro"   @selected(old('posicao', $modal?->posicao ?? 'centro') === 'centro')>Centro (destaque)</option>
            <option value="direito"  @selected(old('posicao', $modal?->posicao ?? 'centro') === 'direito')>Canto inferior direito</option>
            <option value="esquerdo" @selected(old('posicao', $modal?->posicao ?? 'centro') === 'esquerdo')>Canto inferior esquerdo</option>
        </select>
    </div>
    <div class="col-md-2">
        <label class="form-label">Borda arredondada</label>
        <div class="input-group">
            <input type="number" name="bordas" class="form-control" min="0" max="24"
                value="{{ old('bordas', $modal?->bordas ?? 12) }}">
            <span class="input-group-text">px</span>
        </div>
    </div>
    <div class="col-md-2">
        <label class="form-label">Cor principal</label>
        <div class="input-group">
            <input type="color" name="cor_primaria" class="form-control form-control-color"
                value="{{ old('cor_primaria', $modal?->cor_primaria ?? '#2a5298') }}" style="max-width:46px">
            <input type="text" class="form-control js-color-sync" maxlength="7"
                value="{{ old('cor_primaria', $modal?->cor_primaria ?? '#2a5298') }}">
        </div>
    </div>
    <div class="col-md-1">
        <label class="form-label">Texto</label>
        <input type="color" name="cor_texto" class="form-control form-control-color w-100"
            value="{{ old('cor_texto', $modal?->cor_texto ?? '#333333') }}">
    </div>
    <div class="col-md-1">
        <label class="form-label">Fundo</label>
        <input type="color" name="cor_fundo" class="form-control form-control-color w-100"
            value="{{ old('cor_fundo', $modal?->cor_fundo ?? '#ffffff') }}">
    </div>
    <div class="col-12">
        <label class="form-label">URL da imagem do topo <span class="text-muted">(opcional)</span></label>
        <input type="url" name="imagem_url" class="form-control"
            value="{{ old('imagem_url', $modal?->imagem_url) }}"
            placeholder="https://seusite.com.br/imagem-banner.jpg">
        <div class="form-text">Imagem exibida no topo do modal (recomendado: 800×300px).</div>
    </div>

    {{-- ── Gatilho ─────────────────────────────────────────────────────────────── --}}
    <div class="col-12 mt-2"><h6 class="text-muted fw-semibold mb-0">Gatilho de exibição</h6><hr class="mt-1 mb-0"></div>

    @php $gatilhoAtual = old('gatilho', $modal?->gatilho ?? 'imediato'); @endphp

    <div class="col-md-4">
        <label class="form-label">Quando exibir</label>
        <select name="gatilho" class="form-select js-gatilho-select" id="gatilho_{{ $modal?->id ?? 'novo' }}">
            <option value="imediato" @selected($gatilhoAtual === 'imediato')>Imediatamente ao carregar</option>
            <option value="delay"    @selected($gatilhoAtual === 'delay')>Após X segundos</option>
            <option value="scroll"   @selected($gatilhoAtual === 'scroll')>Ao rolar X% da página</option>
            <option value="elemento" @selected($gatilhoAtual === 'elemento')>Ao chegar em um elemento</option>
            <option value="saida"    @selected($gatilhoAtual === 'saida')>Ao tentar sair da página</option>
        </select>
    </div>
    <div class="col-md-4 js-gatilho-valor" id="gatilho_valor_wrap_{{ $modal?->id ?? 'novo' }}"
        style="{{ in_array($gatilhoAtual, ['delay','scroll','elemento']) ? '' : 'display:none' }}">
        <label class="form-label js-gatilho-label">
            @if($gatilhoAtual === 'delay') Segundos
            @elseif($gatilhoAtual === 'scroll') Porcentagem (%)
            @elseif($gatilhoAtual === 'elemento') Seletor CSS
            @else Valor
            @endif
        </label>
        <input type="text" name="gatilho_valor" class="form-control"
            value="{{ old('gatilho_valor', $modal?->gatilho_valor) }}"
            placeholder="{{ $gatilhoAtual === 'delay' ? '5' : ($gatilhoAtual === 'scroll' ? '60' : '#contato') }}">
        <div class="form-text js-gatilho-hint">
            @if($gatilhoAtual === 'delay') Modal aparece após N segundos.
            @elseif($gatilhoAtual === 'scroll') Modal aparece quando o usuário rolar N% da página.
            @elseif($gatilhoAtual === 'elemento') ID ou classe CSS do elemento que aciona o modal (ex: <code>#contato</code>).
            @endif
        </div>
    </div>
    <div class="col-md-4 d-flex align-items-end">
        <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" name="mostrar_uma_vez" value="1"
                id="mostrar_uma_vez_{{ $modal?->id ?? 'novo' }}"
                @checked(old('mostrar_uma_vez', $modal?->mostrar_uma_vez ?? true))>
            <label class="form-check-label" for="mostrar_uma_vez_{{ $modal?->id ?? 'novo' }}">
                Mostrar apenas uma vez por visitante
            </label>
            <div class="form-text">Usa localStorage para lembrar visitantes que já viram o modal.</div>
        </div>
    </div>

    {{-- ── Ativo ─────────────────────────────────────────────────────────────── --}}
    <div class="col-12">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="ativo" value="1"
                id="ativo_{{ $modal?->id ?? 'novo' }}"
                @checked(old('ativo', $modal?->ativo ?? true))>
            <label class="form-check-label" for="ativo_{{ $modal?->id ?? 'novo' }}">Widget ativo</label>
        </div>
    </div>
</div>

{{-- Script para dinamismo do select de gatilho --}}
<script>
(function() {
    function initGatilhoSelect(sel) {
        var wrap  = document.getElementById('gatilho_valor_wrap_' + (sel.dataset.id || 'novo'));
        var label = wrap ? wrap.querySelector('.js-gatilho-label') : null;
        var hint  = wrap ? wrap.querySelector('.js-gatilho-hint') : null;
        var input = wrap ? wrap.querySelector('input') : null;

        var labels = { delay: 'Segundos', scroll: 'Porcentagem (%)', elemento: 'Seletor CSS' };
        var hints  = {
            delay:    'Modal aparece após N segundos.',
            scroll:   'Modal aparece quando o usuário rolar N% da página.',
            elemento: 'ID ou classe CSS do elemento (ex: <code>#contato</code> ou <code>.secao-contato</code>).',
        };
        var placeholders = { delay: '5', scroll: '60', elemento: '#contato' };

        sel.addEventListener('change', function() {
            var v = sel.value;
            if (['delay','scroll','elemento'].includes(v)) {
                if (wrap) wrap.style.display = '';
                if (label) label.textContent = labels[v] || 'Valor';
                if (hint)  hint.innerHTML    = hints[v]  || '';
                if (input) input.placeholder  = placeholders[v] || '';
            } else {
                if (wrap) wrap.style.display = 'none';
            }
        });
    }

    document.querySelectorAll('.js-gatilho-select').forEach(function(sel) {
        var id = sel.id.replace('gatilho_', '');
        sel.dataset.id = id;
        initGatilhoSelect(sel);
    });
})();
</script>
