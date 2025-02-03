<div class="form-group">
    <label for="selectModalidades">Selecione uma modalidade:</label>
    <select id="selectModalidades" class="form-control" name="modalidade_id">
        <option value="">Selecione</option>
        @foreach ($modalidades as $modalidade)
            <option value="{{ $modalidade->id }}" 
                {{ old('modalidade_id', $selectedModalidade) == $modalidade->id ? 'selected' : '' }}>
                {{ $modalidade->nome }}
            </option>
        @endforeach
    </select>
</div>
