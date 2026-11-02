<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Twilio\Security\RequestValidator;

/**
 * Webhooks do Twilio (WhatsApp/SMS): so aceita chamada assinada pelo Twilio (cabecalho X-Twilio-Signature,
 * HMAC-SHA1 do token da conta sobre a URL + parametros). Antes qualquer pessoa chamava /api/webhook/twilio e fazia
 * a empresa enviar WhatsApp para qualquer numero, alem de gastar IA.
 */
class VerificaAssinaturaTwilio
{
    public function handle(Request $request, Closure $next)
    {
        $token = config('services.twilio.token');
        abort_unless($token, 503, 'Twilio nao configurado.');

        $assinatura = (string) $request->header('X-Twilio-Signature');
        $validador = new RequestValidator($token);

        // a URL assinada e a que o Twilio chamou; tenta a recebida e a montada a partir de APP_URL
        $urls = array_unique([$request->fullUrl(), rtrim((string) config('app.url'), '/') . $request->getRequestUri()]);

        foreach ($urls as $url) {
            if ($assinatura !== '' && $validador->validate($assinatura, $url, $request->post())) {
                return $next($request);
            }
        }

        abort(403, 'Assinatura invalida.');
    }
}
