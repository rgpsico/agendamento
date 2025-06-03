<?php

namespace App\Services;

use App\Models\Agendamento;
use Illuminate\Support\Facades\Log;

class AgendamentoService
{
    public function criarAgendamento(array $dados): Agendamento
    {
        return Agendamento::create([
            'aluno_id' => $dados['aluno_id'],
            'professor_id' => $dados['professor_id'],
            'modalidade_id' => $dados['modalidade_id'],
            'data_da_aula' => $dados['data_aula'],
            'horario' => $dados['hora_aula'],
            'valor_aula' => $dados['value'],
            'status' => 'PENDING',
            'usuario_id' => $dados['usuario_id'],
        ]);
    }
}
