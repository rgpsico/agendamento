<!-- resources/views/components/AlunosComponent.blade.php -->
<div class="form-group">
    <label for="selectModalidades">Selecione uma modalidade:</label>
    <select id="selectModalidades" class="form-control" name="modalidade_id">
        <option value="" >Selecione</option>
        @foreach ($modalidades as $modalidade)
            {{-- Verifica se o valor antigo (old) existe e se é igual ao ID da modalidade atual --}}
            @php
                $selected = '';
                if (old('modalidade_id') && old('modalidade_id') == $modalidade->id) {
                    $selected = 'selected';
                }
            @endphp

            <option value="{{ $modalidade->id }}" {{ $selected }}>{{ $modalidade->nome }}</option>
        @endforeach
    </select>
</div>
