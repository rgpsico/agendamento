<div class="mb-3">
    <label class="form-label">Nome do template <span class="text-danger">*</span></label>
    <input type="text" name="nome" class="form-control @error('nome') is-invalid @enderror"
        value="{{ old('nome', $template?->nome) }}" required placeholder="Ex: Prospecção inicial">
    @error('nome')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label class="form-label">Assunto do e-mail <span class="text-danger">*</span></label>
    <input type="text" name="assunto" class="form-control @error('assunto') is-invalid @enderror"
        value="{{ old('assunto', $template?->assunto) }}" required placeholder="Ex: Olá {nome}, conheça nossa plataforma">
    @error('assunto')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label class="form-label">Corpo do e-mail (HTML permitido) <span class="text-danger">*</span></label>
    <textarea name="corpo" rows="12" class="form-control @error('corpo') is-invalid @enderror"
        required placeholder="Use {nome}, {email}, {telefone}, {empresa}, {interesse} como variáveis">{{ old('corpo', $template?->corpo) }}</textarea>
    @error('corpo')<div class="invalid-feedback">{{ $message }}</div>@enderror
    <div class="form-text">Variáveis disponíveis: <code>{nome}</code> <code>{email}</code> <code>{telefone}</code> <code>{empresa}</code> <code>{interesse}</code></div>
</div>

<div class="form-check">
    <input class="form-check-input" type="checkbox" name="ativo" value="1" id="ativo_{{ $template?->id ?? 'novo' }}"
        @checked(old('ativo', $template?->ativo ?? true))>
    <label class="form-check-label" for="ativo_{{ $template?->id ?? 'novo' }}">Template ativo</label>
</div>
