<?php

namespace App\Http\Controllers;

use App\Models\Esporte;
use App\Models\Modalidade;
use Illuminate\Http\Request;

class ModalidadeController extends Controller
{
    protected $pageTitle, $model;

    public function __construct(Esporte $model)
    {
        $this->model = $model;
        $this->pageTitle = 'Listar Modalidade';
    }

    public function index()
    {
        $pageTitle =  $this->pageTitle;
        $model = $this->model::all();
        return view('admin.modalidade.index', compact('model', 'pageTitle'));
    }

    public function create()
    {
        $model =  $this->model;
        $pageTitle =  $this->pageTitle;
        return view('admin.modalidade.create', compact('model', 'pageTitle'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'nome' => 'required',
        ]);

        $this->model::create($request->all());

        return redirect()->route('modalidade.index')->with('success', 'Modalidade criada com sucesso!');
    }

    public function show($id)
    {
        $model = $this->model::find($id);
        return view('admin.aluno.modalidade.show', compact('model', 'pageTitle'));
    }

    public function edit($id)
    {
        $model = $this->model::find($id);
        return view('admin.aluno.modalidade.edit', compact('model', 'pageTitle'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'nome' => 'required',
        ]);

        $model = $this->model::find($id);
        $model->update($request->all());

        return redirect()->route('modalidade.index')->with('success', 'Modalidade atualizada com sucesso!');
    }

    public function destroy($id)
    {
        $this->model::find($id)->delete();
        return redirect()->route('modalidade.index')->with('success', 'Modalidade excluída com sucesso!');
    }
}
