<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteConfiguracao extends Model
{
    protected $table = 'site_configuracoes';

    protected $fillable = [
        'site_id',
        'chave', // nome da configuração
        'valor'  // valor da configuração (pode ser JSON)
    ];

    protected $casts = [
        'valor' => 'json', // permite armazenar boolean, array ou qualquer valor
    ];

    public function site()
    {
        return $this->belongsTo(EmpresaSite::class, 'site_id');
    }
}
