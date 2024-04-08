<div class="form-group">
    <label for="modalidade">{{$label}}</label>
    <select class="form-control" name="modalidade_id">
        <option value="">Selecione</option>
        @isset($pagamento)
            @foreach($pagamento as $pagamento)
                <option value="{{ $pagamento->id }}"                         
                    {{ (old('pagamento_id') == $pagamento->id || isset($model->pagamento_id) && $model->pagamento_id == $pagamento->id) ? 'selected' : '' }}>
                    {{$pagamento->nome}}
                </option>
            @endforeach
        @endisset
    </select>

    @error('pagamento')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>
