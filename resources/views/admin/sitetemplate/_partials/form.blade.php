<div class="form-group mb-3">
    <label for="titulo">Título</label>
    <input type="text" name="titulo" id="titulo" class="form-control" 
           value="{{ old('titulo', $model->titulo ?? '') }}" required>
</div>

<div class="form-group mb-3">
    <label for="slug">Slug</label>
    <input type="text" name="slug" id="slug" class="form-control" 
           value="{{ old('slug', $model->slug ?? '') }}" required>
</div>

<div class="form-group mb-3">
    <label for="descricao">Descrição</label>
    <textarea name="descricao" id="descricao" class="form-control" rows="3">{{ old('descricao', $model->descricao ?? '') }}</textarea>
</div>

<div class="form-group mb-3">
    <label for="path_view">Path da View</label>
    <input type="text" name="path_view" id="path_view" class="form-control" 
           value="{{ old('path_view', $model->path_view ?? '') }}" required>
    <small class="form-text text-muted">Exemplo: <code>templates.default</code></small>
</div>

<div class="form-group mb-3">
    <label for="preview_image">Imagem de Preview</label>
    <input type="file" name="preview_image" id="preview_image" class="form-control">

    @if(!empty($model->preview_image))
        <div class="mt-2">
            <img src="{{ asset('storage/'.$model->preview_image) }}" alt="Preview" width="150">
        </div>
    @endif
</div>
