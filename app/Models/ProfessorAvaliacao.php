<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfessorAvaliacao extends Model
{
    use HasFactory;

    protected $table = 'professor_avaliacoes';

    protected $fillable = ['professor_id', 'usuario_id', 'agendamento_id', 'nota', 'comentario'];

    // Relacionamento com Professor
    public function professor()
    {
        return $this->belongsTo(Professor::class);
    }

    // Relacionamento com Usuário (Aluno)
    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    // Relacionamento com Agendamento
    public function agendamento()
    {
        return $this->belongsTo(Agendamento::class);
    }
}
