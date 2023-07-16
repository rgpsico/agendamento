<div class="form-group">
    <label for="modalidade">{{$label}}</label>
    <select class="form-control" name="modalidade_id">
        <option value="">Selecione</option>
        @isset($modalidades)
            @foreach($modalidades as $modalidade)
                <option value="{{ $modalidade->id }}"                         
                    {{ (old('modalidade_id') == $modalidade->id || isset($model->modalidade_id) && $model->modalidade_id == $modalidade->id) ? 'selected' : '' }}>
                    {{$modalidade->nome}}
                </option>
            @endforeach
        @endisset
    </select>

    @error('modalidade')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>
