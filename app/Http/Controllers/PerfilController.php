<?php 


namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Perfil;
use Illuminate\Http\Request;

class PerfilController extends Controller
{
    public function index()
    {
        $perfis = Perfil::all();
        return view('admin.perfis.index', compact('perfis'));
    }

    public function create()
    {
        return view('admin.perfis.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'tipo' => 'nullable|string|max:255',
        ]);

        Perfil::create($request->only('nome', 'tipo'));

        return redirect()->route('admin.perfis.index')->with('success', 'Perfil criado com sucesso!');
    }

    public function edit(Perfil $perfil)
    {
        return view('admin.perfis.edit', compact('perfil'));
    }

    public function update(Request $request, Perfil $perfil)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'tipo' => 'nullable|string|max:255',
        ]);

        $perfil->update($request->only('nome', 'tipo'));

        return redirect()->route('admin.perfis.index')->with('success', 'Perfil atualizado com sucesso!');
    }

    public function destroy(Perfil $perfil)
    {
        $perfil->delete();
        return redirect()->route('admin.perfis.index')->with('success', 'Perfil excluído com sucesso!');
    }
}
