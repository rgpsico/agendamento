<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agendamento;
use App\Models\ProfessorAvaliacao;

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


    public function getAvaliacoes($agendamento_id)
    {
        $avaliacao = ProfessorAvaliacao::where('agendamento_id', $agendamento_id)
            ->where('usuario_id', auth()->id())
            ->first();

        return response()->json([
            'avaliacao' => $avaliacao
        ]);
    }
    public function storeAvaliacao(Request $request)
    {
        $request->validate([
            'agendamento_id' => 'required|exists:agendamentos,id',
            'nota' => 'required|integer|min:1|max:5',
            'comentario' => 'nullable|string|max:500',
        ]);

        // Obtém os dados do agendamento para encontrar o professor
        $agendamento = Agendamento::findOrFail($request->agendamento_id);

        // Verifica se já existe uma avaliação feita por este usuário para esta aula
        $avaliacao = ProfessorAvaliacao::where('agendamento_id', $request->agendamento_id)
            ->where('usuario_id', auth()->id())
            ->first();

        if ($avaliacao) {
            // Se já existir, fazemos um UPDATE
            $avaliacao->update([
                'nota' => $request->nota,
                'comentario' => $request->comentario,
            ]);

            return response()->json([
                'message' => 'Avaliação atualizada com sucesso!',
                'avaliacao' => $avaliacao
            ]);
        } else {
            // Se não existir, criamos um novo registro
            $avaliacao = ProfessorAvaliacao::create([
                'professor_id' => $agendamento->professor_id,
                'usuario_id' => auth()->id(),
                'agendamento_id' => $request->agendamento_id,
                'nota' => $request->nota,
                'comentario' => $request->comentario,
            ]);

            return response()->json([
                'message' => 'Avaliação salva com sucesso!',
                'avaliacao' => $avaliacao
            ]);
        }
    }
}
