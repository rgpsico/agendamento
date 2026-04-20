<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\Request;

class LeadInteresseController extends Controller
{
    public function show(string $token)
    {
        $lead = Lead::where('token', $token)->firstOrFail();

        if ($lead->interessado_em) {
            return view('leads.interesse-confirmado', compact('lead'));
        }

        // Vira morno na primeira vez que abre o link
        if (!$lead->morno_em) {
            $lead->update(['morno_em' => now()]);
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

        return view('leads.interesse-confirmado', compact('lead'));
    }
}
