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

        $request->validate([
            'whatsapp' => 'required|string|max:20',
        ]);

        $lead->update([
            'whatsapp_confirmado' => $request->whatsapp,
            'interessado_em'      => now(),
            'status'              => 'em_contato',
        ]);

        if (!$lead->trial_usuario_id) {
            $senhaClear = Str::random(8);

            $usuarioTrial = Usuario::create([
                'nome'         => $lead->nome,
                'email'        => $lead->email,
                'password'     => Hash::make($senhaClear),
                'tipo_usuario' => 'trial',
                'telefone'     => $request->whatsapp,
            ]);

            $lead->update(['trial_usuario_id' => $usuarioTrial->id]);

            Mail::to($lead->email)->send(new LeadAcessoTrialMail($lead, $senhaClear));
        }

        return view('leads.interesse-confirmado', compact('lead'));
    }
}
