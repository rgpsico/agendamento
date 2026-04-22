<?php

namespace App\Http\Controllers;

use App\Mail\LeadAcessoTrialMail;
use App\Models\Lead;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class LeadInteresseController extends Controller
{
    public function rastrear(string $token)
    {
        $lead = Lead::where('token', $token)->firstOrFail();

        if (!$lead->morno_em) {
            $lead->update(['morno_em' => now()]);
        }

        return redirect()->route('lead.interesse', $token);
    }

    public function show(string $token)
    {
        $lead = Lead::where('token', $token)->firstOrFail();

        if ($lead->interessado_em) {
            return view('leads.interesse-confirmado', compact('lead'));
        }

        return view('leads.interesse', compact('lead'));
    }

    public function store(Request $request, string $token)
    {
        $lead = Lead::where('token', $token)->firstOrFail();

        $lead->update([
            'interessado_em' => now(),
            'status'         => 'em_contato',
        ]);

        if (!$lead->trial_usuario_id) {
            $senhaClear = Str::random(8);

            $usuario = Usuario::updateOrCreate(
                ['email' => $lead->email],
                [
                    'nome'         => $lead->nome,
                    'password'     => Hash::make($senhaClear),
                    'tipo_usuario' => 'Professor',
                    'telefone'     => $lead->telefone,
                ]
            );

            $lead->update(['trial_usuario_id' => $usuario->id]);

            Mail::to($lead->email)->send(new LeadAcessoTrialMail($lead, $senhaClear));
        }

        return view('leads.interesse-confirmado', compact('lead'));
    }

    public function reenviar(string $token)
    {
        $lead = Lead::where('token', $token)->firstOrFail();

        if (!$lead->email || !$lead->trial_usuario_id) {
            return back()->with('erro', 'Não foi possível reenviar. Complete o formulário primeiro.');
        }

        $senhaClear = Str::random(8);

        Usuario::where('id', $lead->trial_usuario_id)->update([
            'password' => Hash::make($senhaClear),
        ]);

        Mail::to($lead->email)->send(new LeadAcessoTrialMail($lead, $senhaClear));

        return back()->with('reenviado', true);
    }
}
