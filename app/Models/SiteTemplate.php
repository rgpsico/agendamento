<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteTemplate extends Model
{
    use HasFactory;

    protected $table = 'site_templates';

    protected $fillable = [
        'titulo',
        'slug',
        'descricao',
        'preview_image',
        'path_view',
    ];

    public function sites()
    {
        return $this->hasMany(EmpresaSite::class, 'template_id');
    }
}
