<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnalyticsEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'empresa_id',
        'event_type',
        'value',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }
}
