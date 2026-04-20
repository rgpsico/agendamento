<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\Request;

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

        return view('leads.interesse-confirmado', compact('lead'));
    }
}
