<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Um celular/navegador do barbeiro que aceitou receber avisos. */
class BarbeariaPushInscricao extends Model
{
    protected $table = 'barbearia_push_inscricoes';

    protected $fillable = ['usuario_id', 'endpoint', 'endpoint_hash', 'chave_p256dh', 'chave_auth', 'aparelho'];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }
}
