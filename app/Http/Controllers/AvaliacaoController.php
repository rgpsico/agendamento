<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agendamento;
use App\Models\EmpresaAvaliacao;
use App\Models\ProfessorAvaliacao;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    public function mediaAvaliacoesPorProfessor($id)
    {
        $mediaAvaliacao = DB::table('professor_avaliacoes')
            ->select('professor_id', DB::raw('AVG(nota) as media_avaliacoes'), DB::raw('COUNT(*) as total_avaliacoes'))
            ->where('professor_id', $id)
            ->groupBy('professor_id')
            ->first();

        if (!$mediaAvaliacao) {
            return response()->json(['message' => 'Professor não encontrado ou sem avaliações'], 404);
        }

        return response()->json($mediaAvaliacao);
    }

    public function storeAvaliacao(Request $request)
    {
        $request->validate([
            'agendamento_id' => 'required|exists:agendamentos,id',
            'nota' => 'required|integer|min:1|max:5',
            'comentario' => 'nullable|string|max:500',
            'empresa_id' => 'nullable|exists:empresa,id',
        ]);

        $userId = Auth::id();
        $agendamento = Agendamento::findOrFail($request->agendamento_id);

        // Salvar/atualizar avaliação do professor
        $avaliacaoProfessor = $this->storeAvaliacaoProfessor($agendamento, $userId, $request->nota, $request->comentario);

        // Salvar/atualizar avaliação da empresa (se fornecida)
        $avaliacaoEmpresa = null;
        if ($request->has('empresa_id')) {
            $avaliacaoEmpresa = $this->storeAvaliacaoEmpresa($agendamento, $request->empresa_id, $userId, $request->nota, $request->comentario);
        }

        return response()->json([
            'message' => 'Avaliação salva com sucesso!',
            'avaliacao_professor' => $avaliacaoProfessor,
            'avaliacao_empresa' => $avaliacaoEmpresa,
        ]);
    }

    // Método para salvar ou atualizar avaliação do professor
    private function storeAvaliacaoProfessor($agendamento, $userId, $nota, $comentario)
    {
        $avaliacaoProfessor = ProfessorAvaliacao::where('agendamento_id', $agendamento->id)
            ->where('usuario_id', $userId)
            ->first();

        if ($avaliacaoProfessor) {
            $avaliacaoProfessor->update([
                'nota' => $nota,
                'comentario' => $comentario,
            ]);
        } else {
            $avaliacaoProfessor = ProfessorAvaliacao::create([
                'professor_id' => $agendamento->professor_id,
                'usuario_id' => $userId,
                'agendamento_id' => $agendamento->id,
                'nota' => $nota,
                'comentario' => $comentario,
            ]);
        }

        return $avaliacaoProfessor;
    }

    // Método para salvar ou atualizar avaliação da empresa
    private function storeAvaliacaoEmpresa($agendamento, $empresaId, $userId, $nota, $comentario)
    {
        $avaliacaoEmpresa = EmpresaAvaliacao::where('agendamento_id', $agendamento->id)
            ->where('empresa_id', $empresaId)
            ->where('user_id', $userId)
            ->first();

        if ($avaliacaoEmpresa) {
            $avaliacaoEmpresa->update([
                'avaliacao' => $nota,
                'comentario' => $comentario,
            ]);
        } else {
            $avaliacaoEmpresa = EmpresaAvaliacao::create([
                'agendamento_id' => $agendamento->id,
                'empresa_id' => $empresaId,
                'user_id' => $userId,
                'avaliacao' => $nota,
                'comentario' => $comentario,
            ]);
        }

        return $avaliacaoEmpresa;
    }
}
