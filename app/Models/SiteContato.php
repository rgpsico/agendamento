<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteContato extends Model
{
    protected $fillable = ['site_id', 'tipo', 'valor'];

    public function site()
    {
        return $this->belongsTo(EmpresaSite::class, 'site_id');
    }
}
