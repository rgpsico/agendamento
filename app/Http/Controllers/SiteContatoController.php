<?php

namespace App\Http\Controllers;

use App\Models\SiteContato;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiteContatoController extends Controller
{
    public function index()
    {
        $site = Auth::user()->empresa->site;

        $contatos = SiteContato::where('site_id', $site->id)->paginate(10);

        return view('admin.site.contatos.index', compact('contatos'));
    }

    public function create()
    {
        return view('admin.site.contatos.create');
    }

    public function store(Request $request)
    {
        $site = Auth::user()->empresa->site;

        $request->validate([
            'tipo' => 'required|string|max:50',
            'valor' => 'required|string|max:255',
        ]);

        SiteContato::create([
            'site_id' => $site->id,
            'tipo' => $request->tipo,
            'valor' => $request->valor,
        ]);

        return redirect()->route('admin.site.contatos.index')->with('success', 'Contato criado com sucesso!');
    }

    public function edit(SiteContato $contato)
    {
        return view('admin.site.contatos.edit', compact('contato'));
    }

    public function update(Request $request, SiteContato $contato)
    {
        $request->validate([
            'tipo' => 'required|string|max:50',
            'valor' => 'required|string|max:255',
        ]);

        $contato->update($request->only(['tipo', 'valor']));

        return redirect()->route('admin.site.contatos.index')->with('success', 'Contato atualizado com sucesso!');
    }

    public function destroy(SiteContato $contato)
    {
        $contato->delete();

        return redirect()->route('admin.site.contatos.index')->with('success', 'Contato removido com sucesso!');
    }
}
