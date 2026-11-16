<?php

namespace App\Services\WhatsApp;

use App\Models\WhatsappCanal;
use Twilio\Rest\Client;

/** Envia mensagens de WhatsApp pelo Twilio a partir do número (sender) de uma empresa. */
class TwilioWhatsApp
{
    /** O Twilio aceita até 1600 caracteres por mensagem de WhatsApp. */
    private const LIMITE = 1500;

    private ?Client $cliente = null;

    /**
     * @return string[] SIDs das mensagens enviadas (uma por parte)
     */
    public function enviar(WhatsappCanal $canal, string $para, string $texto): array
    {
        $de = 'whatsapp:' . WhatsappCanal::normalizarNumero($canal->numero);
        $para = 'whatsapp:' . WhatsappCanal::normalizarNumero($para);

        $sids = [];
        foreach (self::partes($texto) as $parte) {
            $sids[] = $this->cliente()->messages->create($para, ['from' => $de, 'body' => $parte])->sid;
        }

        return $sids;
    }

    /** Quebra o texto em partes de até LIMITE caracteres, preferindo parágrafos e frases. */
    public static function partes(string $texto): array
    {
        $texto = trim($texto);
        if ($texto === '') {
            return [];
        }

        $partes = [];
        while (mb_strlen($texto) > self::LIMITE) {
            $pedaco = mb_substr($texto, 0, self::LIMITE);
            $corte = max(mb_strrpos($pedaco, "\n\n") ?: 0, mb_strrpos($pedaco, "\n") ?: 0);
            if ($corte < self::LIMITE / 2) {
                $corte = max($corte, (mb_strrpos($pedaco, '. ') ?: 0) + 1, mb_strrpos($pedaco, ' ') ?: 0);
            }
            if ($corte < self::LIMITE / 2) {
                $corte = self::LIMITE;
            }
            $partes[] = trim(mb_substr($texto, 0, $corte));
            $texto = trim(mb_substr($texto, $corte));
        }
        $partes[] = $texto;

        return array_values(array_filter($partes, fn ($p) => $p !== ''));
    }

    private function cliente(): Client
    {
        return $this->cliente ??= new Client(config('services.twilio.sid'), config('services.twilio.token'));
    }
}
