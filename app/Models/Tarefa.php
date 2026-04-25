<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarefa extends Model
{
    use HasFactory;

    protected $table = 'tarefas';

    protected $fillable = [
        'tenant_id',
        'lead_id',
        'aluno_id',
        'tipo',
        'descricao',
        'vencimento',
        'concluida',
        'user_id',
    ];

    protected $casts = [
        'vencimento' => 'datetime',
        'concluida' => 'boolean',
    ];

    public function tenant()
    {
        return $this->belongsTo(Empresa::class, 'tenant_id');
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class, 'lead_id');
    }

    public function aluno()
    {
        return $this->belongsTo(Alunos::class, 'aluno_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'user_id');
    }

    public function scopeForTenant($query, int $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }
}
