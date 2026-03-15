<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perfil extends Model
{
    protected $fillable = ['nome', 'tipo'];
    protected $table = 'perfis';

    public function usuarios()
    {
        return $this->belongsToMany(Usuario::class, 'usuario_perfis')
                    ->withPivot('meta')
                    ->withTimestamps();
    }
}




















