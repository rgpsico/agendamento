<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $fillable = ['user_id', 'empresa_id', 'title', 'message', 'status', 'type'];

    // Relacionamento com o usuário
    public function user()
    {
        return $this->belongsTo(Usuario::class, 'user_id');
    }

    // Relacionamento com a empresa
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    // Mutator para formatar a data 'created_at' no formato BR (dd/mm/yyyy)
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d/m/Y'); // Formato dd/mm/yyyy
    }
}
