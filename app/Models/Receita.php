<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receita extends Model
{
    use HasFactory, \App\Models\Concerns\DaEmpresaLogada;

    protected $table = 'receitas'; // Ajuste se necessário

    protected $fillable = [
        'descricao',
        'pagamento_id',
        'valor',
        'data',
        'categoria_id',
        'usuario_id',
        'status',
        'empresa_id',
        'data_recebimento',
        'data_vencimento',
        'metodo_pagamento',
    ];

    /** Formas de pagamento de uma receita lançada no painel. */
    public const METODOS = [
        'PIX'      => 'Pix',
        'DINHEIRO' => 'Dinheiro',
        'CARTAO'   => 'Cartão',
        'BOLETO'   => 'Boleto',
        'OUTRO'    => 'Outro',
    ];

    /**
     * Data que vale para relatórios (regime de caixa): recebimento; se ainda não recebida, vencimento;
     * sem nenhuma das duas, a data do lançamento.
     */
    public const DATA_COMPETENCIA_SQL = 'DATE(COALESCE(receitas.data_recebimento, receitas.data_vencimento, receitas.created_at))';

    /** Receitas cuja data de competência está no período (datas Y-m-d, inclusive). */
    public function scopeNoPeriodo($query, string $inicio, string $fim)
    {
        return $query->whereRaw(self::DATA_COMPETENCIA_SQL . ' BETWEEN ? AND ?', [$inicio, $fim]);
    }

    public function getDataCompetenciaAttribute(): ?\Carbon\Carbon
    {
        return $this->data_recebimento ?? $this->data_vencimento ?? $this->created_at;
    }

    /** Forma de pagamento: a da própria receita (lançamento manual) ou a do pagamento do agendamento. */
    public function getMetodoAttribute(): ?string
    {
        return $this->metodo_pagamento ?: $this->pagamento?->metodo_pagamento;
    }

 protected $casts = [
    'data' => 'datetime',
    'data_vencimento' => 'datetime',
    'data_recebimento' => 'datetime',
];

    // Relacionamentos necessários
    public function categoria()
    {
        return $this->belongsTo(FinanceiroCategoria::class, 'categoria_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function pagamento()
    {
        return $this->belongsTo(Pagamento::class); // Assumindo que Receita tem 'pagamento_id' ou similar
    }
}