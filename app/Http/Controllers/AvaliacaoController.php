<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agendamento;

class AvaliacaoController extends Controller
{
    public function store(Request $request)
    {
        // Validação dos dados
        $request->validate([
            'agendamento_id' => 'required|exists:agendamentos,id',
            'status' => 'required|string',
            'comentario' => 'nullable|string',
        ]);

        // Atualiza o status do agendamento
        $agendamento = Agendamento::findOrFail($request->agendamento_id);
        $agendamento->status = $request->status;
        $agendamento->comentario = $request->comentario;
        $agendamento->save();

        return redirect()->back()->with('success', 'Avaliação salva com sucesso!');
    }
}
