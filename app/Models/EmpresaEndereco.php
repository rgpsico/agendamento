<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfessorEndereco extends Model
{
    protected $table = 'professor_endereco';

    use HasFactory;

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['id', 'professsor_id', 'endereco', 'cidade', 'estado', 'cep'];

    public function professor()
    {
        return $this->belongsTo(Professor::class, 'professor_id', 'id');
    }
}
