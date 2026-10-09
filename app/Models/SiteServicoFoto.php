<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SiteServicoFoto extends Model
{
    protected $table = 'site_servico_fotos';

    protected $fillable = ['site_servico_id', 'caminho', 'credito', 'credito_url', 'ordem'];

    public function servico(): BelongsTo
    {
        return $this->belongsTo(SiteServico::class, 'site_servico_id');
    }
}
