<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteCliqueWhatsapp extends Model
{
    protected $table = "site_cliques_whatsapp";

    protected $fillable = ['empresa_site_id', 'ip', 'user_agent'];



    use HasFactory;
}
