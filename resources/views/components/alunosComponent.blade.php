<!-- resources/views/components/AlunosComponent.blade.php -->
<div>
    @if ($alunos->isEmpty())
        <p>Nenhum aluno encontrado para este professor.</p>
    @else
        <div class="form-group">
            <label for="selectAlunos">Selecione um aluno:</label>
            <select id="selectAlunos" class="form-control" name="aluno_id">
                <option value="">Selecione</option>
                @foreach ($alunos as $aluno)
                    {{-- Verifica se o valor antigo (old) existe e se é igual ao ID do aluno atual --}}
                    @php
                        $selected = '';
                        if (old('aluno_id') && old('aluno_id') == $aluno->id) {
                            $selected = 'selected';
                        }
                    @endphp

                    <option value="{{ $aluno->id }}" {{ $selected }}>{{ $aluno->usuario->nome }}</option>
                @endforeach
            </select>
        </div>
    @endif
</div>
