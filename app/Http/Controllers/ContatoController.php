<?php 

namespace App\Http\Controllers;

use App\Mail\ContatoMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContatoController extends Controller
{
    public function enviar(Request $request)
    {
        $dados = $request->validate([
            'nome' => 'required|string',
            'email' => 'required|email',
            'telefone' => 'nullable|string',
            'servico' => 'required|string',
            'mensagem' => 'required|string',
        ]);

        // e-mail do professor/dono da empresa
        $destinatario = "professor@empresa.com";

        Mail::to($destinatario)->send(new ContatoMail($dados));

        return back()->with('success', 'Mensagem enviada com sucesso!');
    }
}
