<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Cliente na fila de um barbeiro (check-in). */
class FilaAtendimento extends Model
{
    const AGUARDANDO = 'aguardando';
    const ATENDENDO = 'atendendo';
    const CONCLUIDO = 'concluido';
    const DESISTIU = 'desistiu';
    const AUSENTE = 'ausente'; // foi chamado e não apareceu

    /** Respostas rápidas que o cliente pode dar ao recado do barbeiro (chave => texto mostrado ao barbeiro). */
    const RESPOSTAS = [
        'aqui' => 'Estou na barbearia',
        'chegando' => 'Estou chegando',
        'demora' => 'Vou demorar uns 10 minutos',
    ];

    protected $table = 'fila_atendimentos';

    protected $fillable = ['empresa_id', 'professor_id', 'aluno_id', 'status', 'chegou_em', 'chamado_em', 'mensagem', 'mensagem_em', 'resposta', 'resposta_em', 'iniciado_em', 'finalizado_em'];

    protected $casts = [
        'chegou_em' => 'datetime',
        'chamado_em' => 'datetime',
        'mensagem_em' => 'datetime',
        'resposta_em' => 'datetime',
        'iniciado_em' => 'datetime',
        'finalizado_em' => 'datetime',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function professor()
    {
        return $this->belongsTo(Professor::class);
    }

    public function aluno()
    {
        return $this->belongsTo(Alunos::class);
    }

    /** Ainda na fila: esperando ou sendo atendido. */
    public function scopeAbertas($query)
    {
        return $query->whereIn('status', [self::AGUARDANDO, self::ATENDENDO]);
    }

    public function aberta(): bool
    {
        return in_array($this->status, [self::AGUARDANDO, self::ATENDENDO], true);
    }

    /** Quantas pessoas estão à frente (1 = é o próximo). 0 quando já está sendo atendido ou saiu. */
    public function posicao(): int
    {
        if ($this->status !== self::AGUARDANDO) {
            return 0;
        }

        $naFrente = static::where('professor_id', $this->professor_id)
            ->where('status', self::AGUARDANDO)
            ->where(fn ($q) => $q->where('chegou_em', '<', $this->chegou_em)
                ->orWhere(fn ($q) => $q->where('chegou_em', $this->chegou_em)->where('id', '<', $this->id)))
            ->count();

        return $naFrente + 1;
    }
}
