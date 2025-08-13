<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SiteCliqueWhatsapp;

class SiteCliqueWhatsappController extends Controller
{
    public function store(Request $request)
    {
        $clique = SiteCliqueWhatsapp::create([
            'empresa_site_id' => $request->empresa_site_id ?? 1, // ou pegar dinamicamente
            'ip' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
        ]);

        return response()->json(['success' => true, 'clique_id' => $clique->id]);
    }
}
