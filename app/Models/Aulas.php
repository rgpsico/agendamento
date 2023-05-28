<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aulas extends Model
{
    use HasFactory;

    protected $fillable = [
        'professor_id',
        'data_hora',
        'local',
        'capacidade'
    ];
}
