<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class UserEvent extends Model
{
    protected $fillable = ['user_id', 'event_type', 'payload', 'ip', 'user_agent', 'source'];
    protected $casts = ['payload' => 'array'];
}
