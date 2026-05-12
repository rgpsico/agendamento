<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class WidgetSite extends Model
{
    protected $table = 'widget_sites';

    protected $fillable = ['tenant_id', 'nome', 'token', 'dominio', 'whatsapp_selector', 'ativo'];

    protected $casts = ['ativo' => 'boolean'];

    protected static function booted(): void
    {
        static::creating(function (self $site) {
            if (empty($site->token)) {
                $site->token = Str::random(40);
            }
        });
    }

    public function scopeForTenant($query, int $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    public function eventos()
    {
        return $this->hasMany(WidgetEvento::class, 'site_id');
    }

    public function trackUrl(): string
    {
        return url('/widget/track/' . $this->token . '.js');
    }

    public function snippet(): string
    {
        return '<script src="' . $this->trackUrl() . '" defer></script>';
    }
}
