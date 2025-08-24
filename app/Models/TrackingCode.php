<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrackingCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_id-',
        'name',
        'provider',
        'code',
        'type',
        'script',
        'status',
    ];

    /**
     * Relacionamento: cada TrackingCode pertence a uma empresa.
     */
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
}
