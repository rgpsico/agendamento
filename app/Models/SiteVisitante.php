<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteVisitante extends Model
{
    use HasFactory;
    protected $table = 'site_visitantes';

    protected $fillable = ['empresa_site_id', 'session_id'];
}
