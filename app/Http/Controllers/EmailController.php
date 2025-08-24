<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TwilioService;
use Illuminate\Support\Facades\Mail;
use App\Models\EmpresaSite;


class EmailController extends Controller
{
    protected $twilio;

    public function __construct(TwilioService $twilio)
    {
        $this->twilio = $twilio;
    }

    public function send(Request $request)
    {
        $to = $request->input("to"); // Ex: 5521990271287
        $message = $request->input("message");

        $response = $this->twilio->sendWhatsApp($to, $message);

        return response()->json([
            "status" => "enviado",
            "sid" => $response->sid
            
        ]);
    }

    public function receive(Request $request)
    {
        $from = $request->input("From");
        $body = $request->input("Body");

        // Exemplo: salvar no log
        \Log::info("Mensagem recebida de $from: $body");

        return response("Mensagem recebida", 200)
                ->header("Content-Type", "text/plain");
    }

    public function teste(Request $request)
    {
        $from = $request->input("From");
        $body = $request->input("Body");

        // Exemplo: salvar no log
        \Log::info("Mensagem recebida de $from: $body");

        // Enviar e-mail
        Mail::raw("Mensagem de $from: $body", function ($message) use ($from) {
            $message->to('rgyr2010@hotmail.com') // para quem vai enviar
                    ->subject('Nova mensagem recebida')
                    ->from('contato@rjpasseios.com.br', 'Sistema de Mensagens'); // remetente
        });

        return response("Mensagem recebida e e-mail enviado", 200)
                ->header("Content-Type", "text/plain");
    }

    public function enviar(Request $request, $siteId)
    {
        $site = EmpresaSite::findOrFail($siteId);

        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'telefone' => 'nullable|string|max:50',
            'email' => 'required|email|max:255',
            'servico' => 'nullable|string|max:255',
            'mensagem' => 'required|string',
        ]);

        // Enviar e-mail
        Mail::send('emails.contato', ['data' => $data, 'site' => $site], function ($message) use ($site, $data) {
            $message->to($site->empresa->user->email ?? 'contato@rjpasseios.com.br')
                    ->subject("Novo contato do site: {$data['nome']}")
                    ->from($data['email'], $data['nome']);
        });

        return back()->with('success', 'Mensagem enviada com sucesso!');
}
}
