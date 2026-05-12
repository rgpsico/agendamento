<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WidgetEvento extends Model
{
    protected $table = 'widget_eventos';

    protected $fillable = [
        'site_id', 'session_id', 'tipo',
        'pagina', 'referrer', 'dispositivo', 'duracao', 'meta',
    ];

    public function site()
    {
        return $this->belongsTo(WidgetSite::class, 'site_id');
    }
}
