<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServicoRequest;
use App\Models\Disponibilidade;
use App\Models\Servicos;
use Illuminate\Http\Request;

class DisponibilidadeController extends Controller
{
    protected $view = 'admin.empresa.disponibilidade';
    protected $pageTitle = 'Serviços';
    protected $route = 'admin.servico';

    protected $model;

    public function __construct(Disponibilidade $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        $model = $this->model->all();
        return view($this->view . '.index', [
            'pageTitle' => $this->pageTitle,
            'model' => $model,
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

    public function edit($id)
    {
        $model = $this->model->find($id);
        return view(
            $this->view . '.create',
            [
                'pageTitle' => $this->pageTitle,
                'model' => $model,
                'view' => $this->view,
                'route' => $this->route
            ]
        );
    }

    public function update(ServicoRequest $request, $id)
    {
        $model = $this->model->find($id);


        if ($model) {
            $model->empresa_id = $request->empresa_id;
            $model->titulo = $request->titulo;
            $model->descricao = $request->descricao;
            $model->preco = $request->preco;
            $model->tempo_de_aula = $request->tempo_de_aula;

            // Se a imagem foi carregada, atualize o atributo da imagem
            if ($request->hasFile('imagem')) {
                $file = $request->file('imagem');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $path = public_path('/servico');
                $file->move($path, $filename);
                $model->imagem  = $filename;
            }

            $model->save();
            return redirect()->route($this->route . '.edit', ['id' => $id])->with('success', 'Serviço atualizado com sucesso!');
        } else {
            return redirect()->route($this->route . '.index')->with('error', 'Serviço não encontrado.');
        }
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
        $empresa_id = $request->empresa_id;
        $titulo = $request->titulo;
        $descricao = $request->descricao;
        $preco = $request->preco;
        $tempo_de_aula = $request->tempo_de_aula;

        $servicos = new Servicos();
        $servicos->empresa_id = $empresa_id;
        $servicos->titulo = $titulo;
        $servicos->descricao = $descricao;
        $servicos->preco = $preco;
        $servicos->tempo_de_aula = $tempo_de_aula;



        if ($request->hasFile('imagem')) {
            $file = $request->file('imagem');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $path = public_path('/servico');
            $file->move($path, $filename);
            $servicos->imagem  = $filename;
        }


        $servicos->save();

        return redirect()->route('admin.servico.edit', ['id' => $servicos->id]);
    }
}
