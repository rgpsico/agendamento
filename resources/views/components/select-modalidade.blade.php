<div class="form-group">
    <label for="modalidade">{{$label}}</label>
    <select class="form-control" name="modalidade">
        <option value="">Selecione</option>
    
            @foreach($modalidades as $modalidade)
                <option value="{{ $modalidade->nome }}"                         
                    {{ (old('modalidade') == $modalidade->nome || isset($model->modalidade) && $model->modalidade == $modalidade->nome) ? 'selected' : '' }}>
                    {{$modalidade->nome}}
                </option>
            @endforeach
    
    </select>

    @error('modalidade')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>
