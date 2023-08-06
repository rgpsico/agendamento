@php
    // Pegue o ID do agendamento a partir da URL.
    $agendamentoId = request()->route('id');

    // Busque o registro de agendamento correspondente.
    $agendamento = \App\Models\Agendamento::find($agendamentoId);
    
    // Pegue o aluno_id desse agendamento.
    $currentAlunoId = $agendamento ? $agendamento->aluno_id : null;
@endphp


<div>
    @if ($alunos->isEmpty())
        <p>Nenhum aluno encontrado para este professor.</p>
    @else

        <div class="form-group">
            <label for="selectAlunos">Selecione um aluno:</label>
            <select id="selectAlunos" class="form-control" name="aluno_id">
                <option value="">Selecione</option>
                @foreach ($alunos as $aluno)
                
                    {{-- Verifica se o valor antigo (old) existe ou se corresponde ao aluno atual --}}
                    @php
                        $selected = '';
                        if ((old('aluno_id') && old('aluno_id') == $aluno->id) || (!old('aluno_id') && isset($currentAlunoId) && $currentAlunoId == $aluno->id)) {
                            $selected = 'selected';
                        }
                       
                      
                    @endphp

                    <option value="{{ $aluno->id }}" {{ $selected }}>{{ $aluno->usuario->nome }}</option>
                @endforeach
            </select>
        </div>
    @endif
</div>
