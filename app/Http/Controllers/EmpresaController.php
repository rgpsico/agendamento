<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Professor;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    protected $pageTitle = "Empresa TESTE";
    protected $view = "empresas";
    protected $route = "empresa";

    public function __construct()
    {
    }

    private function loadView($viewSuffix = 'index', $data = [])
    {
        $defaultData = [
            'pageTitle' => $this->pageTitle,
            'route' => $this->route
        ];

        $mergedData = array_merge($defaultData, $data);

        return view('admin.' . $this->view . '.' . $viewSuffix, $mergedData);
    }


    public function update($id, Request $request)
    {

        $professor = Professor::findOrFail($id);

        $data = $request->validate([
            'nome_escola' => 'required|max:255',
            'descricao' => 'required',
            'telefone' => 'required',
            'cep' => 'required',
            'rua' => 'required',
            'numero' => 'required',
            'logo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $data['logo'] = $path;
        }



        $professor->update($data);



        return redirect()->route('empresa.configuracao')->with('success', 'Empresa atualizada com sucesso');
    }

    public function index()
    {

        return $this->loadView();
    }

    public function create()
    {
        return $this->loadView('create');
    }

    public function show($id)
    {
        return $this->loadView('show');
    }

    public function configuracao()
    {
        return view(
            'admin.empresas.treinoform',
            ['pageTitle' =>  'Configuração']
        );
    }
}
