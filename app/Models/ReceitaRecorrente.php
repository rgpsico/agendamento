<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceitaRecorrente extends Model
{
    use HasFactory, \App\Models\Concerns\DaEmpresaLogada, \App\Models\Concerns\LancamentoRecorrente;

    protected $table = 'receitas_recorrentes';

    protected $fillable = [
        'descricao',
        'valor',
        'frequencia',
        'data_inicio',
        'data_fim',
        'empresa_id',
        'usuario_id',
        'categoria_id',
        'status'
    ];

    /**
     * A coluna é enum('diario','semanal','mensal','anual'); o formulário manda DIARIA/SEMANAL/MENSAL/ANUAL
     * (padrão das despesas). Sem converter, "DIARIA" não cabe no enum e a gravação falha.
     */
    public function setFrequenciaAttribute($valor): void
    {
        $mapa = ['DIARIA' => 'diario', 'DIARIO' => 'diario', 'SEMANAL' => 'semanal', 'MENSAL' => 'mensal', 'ANUAL' => 'anual'];
        $this->attributes['frequencia'] = $mapa[strtoupper((string) $valor)] ?? $valor;
    }

    protected $casts = [
        'valor' => 'decimal:2',
        'data_inicio' => 'date',   // garante Carbon
        'data_fim' => 'date',      // garante Carbon ou null
    ];

    // Em DespesaRecorrente ou ReceitaRecorrente
    public function categoria()
    {
        return $this->belongsTo(FinanceiroCategoria::class, 'categoria_id');
    }


    // Relacionamentos
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function scopeAtivas($query)
    {
        return $query->where('status', 'ACTIVE');
    }
}
