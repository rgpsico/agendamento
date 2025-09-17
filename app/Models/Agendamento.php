<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agendamento extends Model
{
    use HasFactory;
    protected $table = "agendamentos";

    protected $fillable = ['aluno_id', 'modalidade_id', 'professor_id', 'data_da_aula', 'valor_aula', 'horario', 'servico_id'];

    protected $dates = ['data_da_aula'];

    public function disponibilidades()
    {
        return $this->hasMany(Disponibilidade::class, 'id_dia');
    }

    public function servico()
    {
        return $this->belongsTo(Servicos::class, 'servico_id', 'id');
    }
    public static function verificarDisponibilidade($request, $tipo_de_horario)
    {

        if ($tipo_de_horario == 'DIA') {
            return false; // Permitir agendamento para horários flexíveis
        }

        return self::where('professor_id', $request->professor_id)
            ->where('data_da_aula', $request->data_aula)
            ->where('horario', $request->hora_aula)
            ->exists();
    }


    public static function criarAgendamento(array $dados)
    {
        return self::create([
            'aluno_id' => $dados['aluno_id'],
            'professor_id' => $dados['professor_id'],
            'modalidade_id' => $dados['modalidade_id'],
            'data_da_aula' => $dados['data_aula'],
            'horario' => $dados['hora_aula'],
            'valor_aula' => $dados['valor_aula'],
            'servico_id' => $dados['servico_id']
        ]);
    }






    public function aluno()
    {
        return $this->belongsTo(Alunos::class);
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id', 'id');
    }


    public function aula()
    {
        return $this->belongsTo(Aulas::class);
    }

    public function professor()
    {
        return $this->belongsTo(Professor::class, 'professor_id', 'id');
    }

    public function modalidade()
    {
        return $this->belongsTo(Modalidade::class, 'modalidade_id', 'id');
    }
}
