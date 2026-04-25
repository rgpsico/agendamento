<div class="form-group">                         
    <div class="mb-3">      
        @php
            $avatarArquivo = isset($model) && $model->avatar ? public_path('avatar/' . $model->avatar) : null;
            $avatarUrl = $avatarArquivo && is_file($avatarArquivo)
                ? asset('avatar/' . $model->avatar)
                : asset('images/placeholder-image.svg');
        @endphp
        <img src="{{ $avatarUrl }}" width="150" height="150" alt="Logo da Empresa">
    </div>
    <label>{{$label ?? 'Logo da Empresa'}}</label>
    <input type="file" class="form-control" name="avatar">
    <small class="text-secondary">Tamanho recomendado <b>150px x 150px</b></small>
</div>
