<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** Número de WhatsApp (sender do Twilio) de uma empresa, atendido pela IA de um bot. */
class WhatsappCanal extends Model
{
    protected $table = 'whatsapp_canais';

    protected $fillable = ['empresa_id', 'bot_id', 'provedor', 'numero', 'ativo', 'ia_ativa'];

    protected $casts = [
        'ativo'    => 'boolean',
        'ia_ativa' => 'boolean',
    ];

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function bot(): BelongsTo
    {
        return $this->belongsTo(Bot::class, 'bot_id');
    }

    /** Bot que atende: o escolhido ou, se nenhum, o primeiro bot ativo da empresa. */
    public function botQueAtende(): ?Bot
    {
        return ($this->bot && $this->bot->status)
            ? $this->bot
            : Bot::where('empresa_id', $this->empresa_id)->where('status', 1)->orderBy('id')->first();
    }

    /** "+55 21 99999-9999", "whatsapp:+5521..." ou "(21) 99999-9999" => "+5521999999999". */
    public static function normalizarNumero(?string $numero): string
    {
        $numero = preg_replace('/^whatsapp:/i', '', trim((string) $numero));
        $digitos = preg_replace('/\D/', '', $numero);

        // Sem DDI: assume Brasil.
        if (! str_starts_with($numero, '+') && strlen($digitos) <= 11) {
            $digitos = '55' . $digitos;
        }

        return $digitos === '' ? '' : '+' . $digitos;
    }
}
