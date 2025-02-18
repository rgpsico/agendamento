<div class="form-group">                         
    <div class="mb-3">      
        @isset($model->avatar)
            <img src="{{ asset('avatar/' . $model->avatar) }}" width="150" height="150" alt="Logo da Escola de Surf">
        @endisset
    </div>
    <label>{{$label ?? 'Logo da Empresa'}}</label>
    <input type="file" class="form-control" name="avatar">
    <small class="text-secondary">Tamanho recomendado <b>150px x 150px</b></small>
</div>
