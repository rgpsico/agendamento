@csrf
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Nome</label>
        <input type="text" name="nome" class="form-control" value="{{ old('nome', $lead->nome ?? '') }}" required>
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Telefone</label>
        <input type="text" name="telefone" class="form-control" value="{{ old('telefone', $lead->telefone ?? '') }}">
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">E-mail</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $lead->email ?? '') }}">
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Origem</label>
        <select name="origem" class="form-control" required>
            @foreach($origens as $key => $label)
                <option value="{{ $key }}" @selected(old('origem', $lead->origem ?? 'whatsapp') === $key)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Pipeline</label>
        <select name="pipeline_status" class="form-control" required>
            @foreach($statuses as $key => $label)
                <option value="{{ $key }}" @selected(old('pipeline_status', $lead->pipeline_status ?? 'novo_lead') === $key)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Interesse</label>
        <input type="text" name="interesse" class="form-control" value="{{ old('interesse', $lead->interesse ?? '') }}" placeholder="Pilates, fisioterapia, estetica...">
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Valor estimado</label>
        <input type="number" step="0.01" min="0" name="valor_estimado" class="form-control" value="{{ old('valor_estimado', $lead->valor_estimado ?? '') }}">
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Campanha</label>
        <select name="campanha_id" class="form-control">
            <option value="">Sem campanha</option>
            @foreach($campanhas as $campanha)
                <option value="{{ $campanha->id }}" @selected((int) old('campanha_id', $lead->campanha_id ?? 0) === $campanha->id)>{{ $campanha->nome }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Responsavel</label>
        <select name="responsavel_id" class="form-control">
            <option value="">Sem responsavel</option>
            @foreach($responsaveis as $responsavel)
                <option value="{{ $responsavel->id }}" @selected((int) old('responsavel_id', $lead->responsavel_id ?? 0) === $responsavel->id)>{{ $responsavel->nome }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-12 mb-3">
        <label class="form-label">Observacoes</label>
        <textarea name="observacoes" class="form-control" rows="5">{{ old('observacoes', $lead->observacoes ?? '') }}</textarea>
    </div>
</div>
<div class="text-end">
    <a href="{{ route('crm.leads.index') }}" class="btn btn-outline-secondary">Cancelar</a>
    <button class="btn btn-primary">Salvar</button>
</div>
