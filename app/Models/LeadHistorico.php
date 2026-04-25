<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadHistorico extends Model
{
    use HasFactory;

    protected $table = 'lead_historico';

    public const UPDATED_AT = null;

    protected $fillable = [
        'lead_id',
        'de_status',
        'para_status',
        'observacao',
        'user_id',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class, 'lead_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'user_id');
    }
}
