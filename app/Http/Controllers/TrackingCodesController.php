<?php

namespace App\Http\Controllers;

use App\Models\TrackingCode;
use App\Models\EmpresaSite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrackingCodesController extends Controller
{


    // Criar um novo tracking code
    public function store(Request $request, $site_id)
    {
        $site = EmpresaSite::findOrFail($site_id);

        if ($site->empresa_id !== Auth::user()->empresa->id) {
            abort(403, 'Acesso não autorizado.');
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'provider' => 'nullable|string|max:255',
            'code' => 'required|string|max:255',
            'type' => 'required|string|in:analytics,ads,pixel,other',
            'script' => 'nullable|string',
            'status' => 'nullable|boolean',
        ]);

        $data['status'] = $request->has('status');
        $data['empresa_site_id'] = $site->id;

        TrackingCode::create($data);

        return back()->with('success', 'Código de rastreamento adicionado com sucesso!');
    }

    // Atualizar um tracking code existente
    public function update(Request $request, $id)
    {
        $tracking = TrackingCode::findOrFail($id);

        $site = $tracking->site;
        if ($site->empresa_id !== Auth::user()->empresa->id) {
            abort(403, 'Acesso não autorizado.');
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'provider' => 'nullable|string|max:255',
            'code' => 'required|string|max:255',
            'type' => 'required|string|in:analytics,ads,pixel,other',
            'script' => 'nullable|string',
            'status' => 'nullable|boolean',
        ]);

        $data['status'] = $request->has('status');

        $tracking->update($data);

        return back()->with('success', 'Código de rastreamento atualizado com sucesso!');
    }

    // Excluir um tracking code
    public function destroy($id)
    {
        $tracking = TrackingCode::findOrFail($id);      

        $tracking->delete();

        return response()->json(['success' => true, 'message' => 'Código de rastreamento removido com sucesso!']);
    }
}
