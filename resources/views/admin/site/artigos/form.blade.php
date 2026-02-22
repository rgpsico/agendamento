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
            <div class="d-flex align-items-center mb-2">
                <button type="button" class="btn btn-outline-secondary btn-sm" id="gerar-ia">
                    Gerar conteúdo com IA
                </button>
                <small class="text-muted ml-2" id="ia-status"></small>
            </div>
            <textarea name="conteudo" id="conteudo" rows="8" class="form-control tinymce-editor" required>{{ old('conteudo', optional($artigo)->conteudo) }}</textarea>
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

<script>
    $(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        const $gerarBotao = $('#gerar-ia');
        const $status = $('#ia-status');
        const $titulo = $('#titulo');
        const $conteudo = $('#conteudo');

        $gerarBotao.on('click', function() {
            const titulo = ($titulo.val() || '').trim();

            if (!titulo) {
                $status.text('Informe o título antes de gerar.');
                return;
            }

            $gerarBotao.prop('disabled', true);
            $status.text('Gerando conteúdo...');

            $.ajax({
                method: 'POST',
                url: '{{ route('admin.site.artigos.generate') }}',
                headers: { 'Accept': 'application/json' },
                data: { titulo },
                success: function(response) {
                    if (response.conteudo) {
                        $conteudo.val(response.conteudo);
                        $status.text('Conteúdo gerado com sucesso.');
                    }
                },
                error: function(xhr) {
                    const message = xhr.responseJSON && xhr.responseJSON.message
                        ? xhr.responseJSON.message
                        : 'Não foi possível gerar o conteúdo agora.';
                    $status.text(message);
                },
                complete: function() {
                    $gerarBotao.prop('disabled', false);
                }
            });
        });
    });
</script>

<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (!window.tinymce) return;

        tinymce.init({
            selector: 'textarea.tinymce-editor',
            height: 420,
            menubar: false,
            plugins: 'lists link image table code fullscreen',
            toolbar: 'undo redo | styles | bold italic underline | alignleft aligncenter alignright | bullist numlist | link image table | code fullscreen',
            automatic_uploads: true,
            image_title: true,
            file_picker_types: 'image',
            file_picker_callback: function(callback, value, meta) {
                if (meta.filetype !== 'image') return;

                const input = document.createElement('input');
                input.setAttribute('type', 'file');
                input.setAttribute('accept', 'image/*');
                input.onchange = function() {
                    const file = this.files && this.files[0];
                    if (!file) return;

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        callback(e.target.result, { title: file.name });
                    };
                    reader.readAsDataURL(file);
                };
                input.click();
            }
        });
    });
</script>
