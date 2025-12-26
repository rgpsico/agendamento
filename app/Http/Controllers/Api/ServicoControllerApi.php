<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Professor;
use App\Models\Servicos;
use App\Models\Usuario;
use App\Models\DisponibilidadeServico;
use App\Models\FinanceiroCategoria;
use Illuminate\Http\Request;

class ServicoControllerApi extends Controller
{
    // Mostrar todos os usuários

    protected $model;

    public function __construct(Servicos $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        $model = $this->model::all();
        return response()->json($model);
    }

    public function byEmpresa($empresaId)
    {
        $servicos = $this->model::where('empresa_id', $empresaId)->get();
        return response()->json($servicos);
    }

    // Criar um novo usuário
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'preco' => 'required|numeric',
            'tempo_de_aula' => 'nullable|integer',
            'tipo_agendamento' => 'required|in:DIA,HORARIO',
            'vagas' => 'required_if:tipo_agendamento,DIA|integer|min:1',
            'categoria_id' => 'nullable|exists:financeiro_categorias,id',
            'empresa_id' => 'required|integer|exists:empresas,id',
            'imagem' => 'nullable|image',
        ]);

        $categoriaId = $request->categoria_id;
        if (!$categoriaId) {
            $categoria = FinanceiroCategoria::firstOrCreate(
                ['nome' => $request->titulo, 'tipo' => 'receita'],
                ['descricao' => 'Categoria gerada automaticamente para o serviço']
            );
            $categoriaId = $categoria->id;
        }

        $servico = new Servicos();
        $servico->empresa_id = $request->empresa_id;
        $servico->titulo = $request->titulo;
        $servico->descricao = $request->descricao;
        $servico->preco = $request->preco;
        $servico->tempo_de_aula = $request->tempo_de_aula;
        $servico->tipo_agendamento = $request->tipo_agendamento;
        $servico->categoria_id = $categoriaId;

        if ($request->hasFile('imagem')) {
            $file = $request->file('imagem');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $path = public_path('/servico');
            $file->move($path, $filename);
            $servico->imagem  = $filename;
        } else {
            $servico->imagem = 'imagem_padrao.jpg';
        }

        $servico->save();

        if ($request->tipo_agendamento === 'DIA') {
            $vagasTotais = $request->vagas ?? 1;

            for ($i = 0; $i < 30; $i++) {
                $data = now()->addDays($i)->format('Y-m-d');

                DisponibilidadeServico::create([
                    'servico_id' => $servico->id,
                    'data' => $data,
                    'vagas_totais' => $vagasTotais,
                    'vagas_reservadas' => 0
                ]);
            }
        }

        return response()->json($servico, 201);
    }

    // Mostrar um usuário específico
    public function show($id)
    {
        $model = $this->model::find($id);
        if ($model) {
            return response()->json($model);
        } else {
            return response()->json(['error' => 'Usuário não encontrado'], 404);
        }
    }

    // Atualizar um usuário específico
    public function update(Request $request, $id)
    {
        $servicos = $this->model::find($id);

        if (!$servicos) {
            return response()->json(['error' => 'Serviço não encontrado'], 404);
        }

        $servicos->empresa_id = $request->empresa_id;
        $servicos->titulo = $request->titulo;
        $servicos->descricao = $request->descricao;
        $servicos->preco = $request->preco;
        $servicos->tempo_de_aula = $request->tempo_de_aula;
        $servicos->tipo_agendamento = $request->tipo_agendamento;
        $servicos->categoria_id = $request->categoria_id;

        if ($request->hasFile('imagem')) {
            if ($servicos->imagem && file_exists(public_path('servico/' . $servicos->imagem))) {
                unlink(public_path('servico/' . $servicos->imagem));
            }

            $file = $request->file('imagem');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $path = public_path('/servico');
            $file->move($path, $filename);
            $servicos->imagem = $filename;
        }

        if ($request->tipo_agendamento === 'DIA') {
            $vagasTotais = $request->vagas ?? 1;

            for ($i = 0; $i < 30; $i++) {
                $data = now()->addDays($i)->format('Y-m-d');

                DisponibilidadeServico::create([
                    'servico_id' => $id,
                    'data' => $data,
                    'vagas_totais' => $vagasTotais,
                    'vagas_reservadas' => 0
                ]);
            }
        }

        $servicos->save();

        return response()->json($servicos);
    }

    // Deletar um usuário específico
    public function destroy($id)
    {
        $user = $this->model::find($id);
        if ($user) {
            $user->delete();
            return response()->json(['success' => 'Serviço deletado com sucesso'], 200);
        } else {
            return response()->json(['error' => 'Serviço não encontrado'], 404);
        }
    }
}
