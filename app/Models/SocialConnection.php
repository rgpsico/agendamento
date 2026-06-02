<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialConnection extends Model
{
    protected $fillable = [
        'empresa_id',
        'facebook_page_id',
        'facebook_page_name',
        'facebook_page_token',
        'instagram_account_id',
        'instagram_username',
        'user_access_token',
        'token_expires_at',
    ];

    protected $casts = [
        'token_expires_at' => 'datetime',
    ];

    protected $hidden = [
        'facebook_page_token',
        'user_access_token',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function hasFacebook(): bool
    {
        return !empty($this->facebook_page_id) && !empty($this->facebook_page_token);
    }

    public function hasInstagram(): bool
    {
        return !empty($this->instagram_account_id);
    }

    public function isExpired(): bool
    {
        if (!$this->token_expires_at) return false;
        return $this->token_expires_at->isPast();
    }
}
