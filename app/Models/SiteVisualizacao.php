<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteVisualizacao extends Model
{
    protected $table = "site_visualizacoes";
    protected $fillable = ['empresa_site_id', 'ip', 'user_agent'];
    use HasFactory;
}
