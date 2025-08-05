<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class GoogleAdsToken extends Model
{
    protected $table = 'google_ads_tokens';

    protected $fillable = [
        'empresa_id',
        'google_account_id',
        'access_token',
        'refresh_token',
        'access_token_expires_at',
    ];

    protected $dates = [
        'access_token_expires_at',
        'created_at',
        'updated_at',
    ];

    /**
     * Verifica se o access token expirou
     */
    public function isAccessTokenExpired()
    {
        return $this->access_token_expires_at === null || $this->access_token_expires_at->isPast();
    }

    /**
     * Relação com Empresa (cliente)
     */
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
}
