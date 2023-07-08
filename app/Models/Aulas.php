<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Aulas extends Model
{
    use HasFactory;

    protected $table = "aulas";



    protected $fillable = [
        'professor_id',
        'dia_id',
        'data_hora',
        'local',
        'capacidade'
    ];

    // protected static function boot()
    // {
    //     parent::boot();

    //     static::creating(function ($model) {
    //         $model->{$model->getKeyName()} = (string) Str::uuid();
    //     });
    // }

    public function professor()
    {
        return $this->belongsTo(Professor::class, 'professor_id');
    }

    public function dia()
    {
        return $this->belongsTo(DiaDaSemana::class, 'dia_id');
    }
}
