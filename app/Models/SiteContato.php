<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteDepoimento extends Model
{
    protected $fillable = ['site_id', 'nome', 'foto', 'nota', 'comentario'];

    public function site()
    {
        return $this->belongsTo(EmpresaSite::class, 'site_id');
    }
}
