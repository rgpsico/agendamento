<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpresaAvaliacao extends Model
{
    use HasFactory;

    protected $table = 'empresa_avaliacao';

    protected $fillable = ['empresa_id', 'avaliacao', 'agendamento_id', 'user_id'];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id', 'id');
    }

    public function avaliacaoMedia()
    {
        return 10;
    }
}
