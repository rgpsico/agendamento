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