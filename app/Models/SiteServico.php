<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteServico extends Model
{
    protected $fillable = ['titulo', 'descricao', 'imagem', 'site_id', 'tipo', 'valor'];

    public function site()
    {
        return $this->belongsTo(EmpresaSite::class, 'site_id');
    }
}
