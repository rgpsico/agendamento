<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServicoRequest;
use App\Models\DiaDaSemana;
use App\Models\Disponibilidade;
use App\Models\DisponibilidadeServico;
use App\Models\Servicos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServicoController extends Controller
{
    protected $view = 'admin.escola.servicos';
    protected $pageTitle = 'Serviços';
    protected $route = 'admin.servico';

    protected $model;

    public function __construct(Servicos $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        $empresa_id = Auth::user()->empresa->id;
        $model = $this->model->where('empresa_id', $empresa_id)->get();

        return view($this->view . '.index', [
            'pageTitle' => $this->pageTitle,
            'model' => $model ?? '',
            'view' => $this->view,
            'route' => $this->route
        ]);
    }

    public function create()
    {
        return view(
            $this->view . '.create',
            [
                'pageTitle' => $this->pageTitle,
                'view' => $this->view,
                'route' => $this->route
            ]
        );
    }

    public function listarServicos()
    {
        $servicos = Servicos::all(); // Busca todos os serviços cadastrados

        return view('admin.empresas.listar_servicos', [
            'pageTitle' => 'Lista de Serviços',
            'servicos' => $servicos
        ]);
    }

    public function configurarHorarios($idServico)
    {
        $servico = Servicos::findOrFail($idServico);
        $diaDaSemana = DiaDaSemana::all();
        $id_professor = Auth::user()->professor->id;

        // Buscar as disponibilidades já cadastradas para esse serviço
        $disponibilidades = Disponibilidade::where('id_professor', $id_professor)
            ->where('id_servico', $idServico)
            ->get();

        return view('admin.empresas.configurar_horarios', [
            'servico' => $servico,
            'diaDaSemana' => $diaDaSemana,
            'disponibilidades' => $disponibilidades
        ]);
    }

    public function salvarHorarios(Request $request, $idServico)
    {
        $id_professor = $request->professor_id;

        // Deletar todas as disponibilidades para evitar duplicações
        Disponibilidade::where('id_professor', $id_professor)
            ->where('id_servico', $idServico)
            ->delete();

        // Percorrer os horários e salvar
        foreach ($request->start as $dia => $horariosInicio) {
            foreach ($horariosInicio as $index => $horaInicio) {
                $horaFim = $request->end[$dia][$index] ?? null;

                if ($horaInicio && $horaFim) {
                    Disponibilidade::create([
                        'id_professor' => $id_professor,
                        'id_servico' => $idServico,
                        'id_dia' => $dia,
                        'hora_inicio' => $horaInicio,
                        'hora_fim' => $horaFim,
                    ]);
                }
            }
        }

        return redirect()->route('configurar.horarios', $idServico)->with('success', 'Horários salvos com sucesso!');
    }




    public function edit($id)
    {
        $model = $this->model->find($id);

        // Busca o número de vagas na tabela DisponibilidadeServico
        $numero_de_vagas = DisponibilidadeServico::where('servico_id', $id)->value('vagas_totais');

        return view(
            $this->view . '.create',
            [
                'numero_de_vagas' => $numero_de_vagas, // Corrigido: adicionada a vírgula
                'pageTitle' => $this->pageTitle,
                'model' => $model,
                'view' => $this->view,
                'route' => $this->route
            ]
        );
    }



    public function update(ServicoRequest $request, $id)
    {
        $servicos = Servicos::findOrFail($id);

        $servicos->empresa_id = $request->empresa_id;
        $servicos->titulo = $request->titulo;
        $servicos->descricao = $request->descricao;
        $servicos->preco = $request->preco;
        $servicos->tempo_de_aula = $request->tempo_de_aula;
        $servicos->tipo_agendamento = $request->tipo_agendamento;

        if ($request->hasFile('imagem')) {
            // Excluir a imagem antiga se existir
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
            $vagasTotais = $request->vagas ?? 1; // Garante que não seja null

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

        return redirect()->route('admin.servico.edit', ['id' => $servicos->id])->with('success', 'Serviço atualizado com sucesso!');
    }


    public function destroy($id)
    {
        $model = $this->model->find($id);
        if ($model) {
            $model->delete();
            return redirect()->route($this->route . '.index')->with('success', 'Serviço excluído com sucesso!');
        } else {
            return redirect()->route($this->route . '.index')->with('error', 'Serviço não encontrado.');
        }
    }

    public function store(ServicoRequest $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'preco' => 'required|numeric',
            'tempo_de_aula' => 'nullable|integer',
            'tipo_agendamento' => 'required|in:DIA,HORARIO',
            'vagas' => 'required_if:tipo_agendamento,DIA|integer|min:1',
        ]);

        $servico = new Servicos();
        $servico->empresa_id = $request->empresa_id;
        $servico->titulo = $request->titulo;
        $servico->descricao = $request->descricao;
        $servico->preco = $request->preco;
        $servico->tempo_de_aula = $request->tempo_de_aula;
        $servico->tipo_agendamento = $request->tipo_agendamento;

        if ($request->hasFile('imagem')) {
            $file = $request->file('imagem');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $path = public_path('/servico');
            $file->move($path, $filename);
            $servico->imagem  = $filename;
        } else {
            // Se não houver imagem enviada, define uma imagem padrão
            $servico->imagem = 'imagem_padrao.jpg'; // Substitua pelo nome da imagem padrão
        }

        $servico->save();

        // Criar disponibilidade para serviços do tipo DIA
        if ($request->tipo_agendamento === 'DIA') {
            $vagasTotais = $request->vagas ?? 1; // Garante que não seja null

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

        return redirect()->route('admin.servico.edit', ['id' => $servico->id]);
    }
}
