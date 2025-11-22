@php
    $artigo = $artigo ?? null;
@endphp

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <label for="titulo">Título *</label>
                    <input type="text" name="titulo" id="titulo" class="form-control"
                        value="{{ old('titulo', optional($artigo)->titulo) }}" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="status">Status *</label>
                    <select name="status" id="status" class="form-control" required>
                        @foreach ($statuses as $value => $label)
                            <option value="{{ $value }}"
                                {{ old('status', optional($artigo)->status ?? \App\Models\SiteArtigo::STATUS_RASCUNHO) == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="resumo">Resumo</label>
            <textarea name="resumo" id="resumo" rows="3" class="form-control"
                placeholder="Resumo exibido nas listagens">{{ old('resumo', optional($artigo)->resumo) }}</textarea>
        </div>

        <div class="form-group">
            <label for="conteudo">Conteúdo *</label>
            <textarea name="conteudo" id="conteudo" rows="8" class="form-control" required>{{ old('conteudo', optional($artigo)->conteudo) }}</textarea>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="imagem_capa">Imagem de capa</label>
                    <input type="file" name="imagem_capa" id="imagem_capa" class="form-control">
                    <small class="form-text text-muted">Formatos JPG, PNG ou WEBP até 2MB.</small>
                </div>
            </div>
            @if ($artigo && $artigo->imagem_capa)
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Pré-visualização atual</label>
                        <div>
                            <img src="{{ asset('storage/' . $artigo->imagem_capa) }}" alt="Imagem atual" class="img-fluid rounded"
                                style="max-height: 180px;">
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="text-right">
            <button type="submit" class="btn btn-primary">Salvar</button>
        </div>
    </div>
</div>
