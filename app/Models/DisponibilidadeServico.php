<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisponibilidadeServico extends Model
{
    use HasFactory;

    protected $fillable = ['servico_id', 'data', 'vagas_totais', 'vagas_reservadas'];

    public function servico()
    {
        return $this->belongsTo(Servicos::class);
    }

    public function vagasDisponiveis()
    {
        return $this->vagas_totais - $this->vagas_reservadas;
    }
}
