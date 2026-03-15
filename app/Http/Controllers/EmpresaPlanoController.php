<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\EmpresaPlano;
use App\Models\Plano;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmpresaPlanoController extends Controller
{
    public function index()
    {
        $empresaId = auth()->user()->empresa->id;
        $empresa = Empresa::findOrFail($empresaId);

        $planos = Plano::all();

        // histórico de planos da empresa
        $historico = EmpresaPlano::with('plano')
            ->where('empresa_id', $empresaId)
            ->orderByDesc('data_inicio')
            ->get();

        // último plano ativo
        $planoAtual = $historico->where('status', 'ativo')->first();

        return view('admin.empresas.planos.index', compact('empresa', 'planos', 'planoAtual', 'historico'));
    }

    public function store(Request $request, $empresaId)
    {
        $data = $request->validate([
            'plano_id' => 'required|exists:planos,id',
        ]);

        // Busca o plano
        $plano = $this->getPlano($data['plano_id']);

        DB::transaction(function() use ($empresaId, $plano) {
            // Cancela o plano atual, se houver
            $this->cancelarPlanoAtual($empresaId);

            // Cria o novo plano
            $this->criarNovoPlano($empresaId, $plano);
        });

        return redirect()->back()->with('success', 'Plano atualizado com sucesso!');
    }

    /**
     * Busca o plano pelo ID
     */
    private function getPlano(int $planoId): Plano
    {
        return Plano::findOrFail($planoId);
    }

    /**
     * Calcula a data de fim com base na periodicidade do plano
     */
    private function calcularDataFim(Plano $plano): ?\Carbon\Carbon
    {
        $dataInicio = now();

        return match($plano->periodicidade) {
            'mensal' => $dataInicio->copy()->addMonth(),
            'trimestral' => $dataInicio->copy()->addMonths(3),
            'anual' => $dataInicio->copy()->addYear(),
            default => null
        };
    }

    /**
     * Cancela o plano ativo atual da empresa
     */
    private function cancelarPlanoAtual(int $empresaId): void
    {
        EmpresaPlano::where('empresa_id', $empresaId)
            ->where('status', 'ativo')
            ->update([
                'status' => 'cancelado',
                'data_fim' => now()
            ]);
    }

    /**
     * Cria um novo plano para a empresa
     */
    private function criarNovoPlano(int $empresaId, Plano $plano): void
    {
        EmpresaPlano::create([
            'empresa_id'  => $empresaId,
            'plano_id'    => $plano->id,
            'status'      => 'ativo',
            'data_inicio' => now(),
            'data_fim'    => $this->calcularDataFim($plano),
            'valor'       => $plano->valor,
        ]);
    }

}
