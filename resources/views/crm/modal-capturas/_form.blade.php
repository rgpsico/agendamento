<div class="row g-3">
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
    <div class="col-md-12">
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
        <label class="form-label">Cor principal</label>
        <div class="input-group">
            <input type="color" name="cor_primaria" class="form-control form-control-color"
                value="{{ old('cor_primaria', $modal?->cor_primaria ?? '#2a5298') }}" style="max-width:60px">
            <input type="text" class="form-control"
                value="{{ old('cor_primaria', $modal?->cor_primaria ?? '#2a5298') }}" readonly>
        </div>
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
        <label class="form-label">Mensagem de sucesso</label>
        <input type="text" name="mensagem_sucesso" class="form-control"
            value="{{ old('mensagem_sucesso', $modal?->mensagem_sucesso ?? 'Recebemos seus dados! Em breve entraremos em contato.') }}">
    </div>
    <div class="col-12">
        <label class="form-label">Campos do formulário</label>
        <div class="d-flex gap-3">
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
    <div class="col-12">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="ativo" value="1"
                id="ativo_{{ $modal?->id ?? 'novo' }}"
                @checked(old('ativo', $modal?->ativo ?? true))>
            <label class="form-check-label" for="ativo_{{ $modal?->id ?? 'novo' }}">Widget ativo</label>
        </div>
    </div>
</div>
