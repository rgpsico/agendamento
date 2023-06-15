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


    public function update(Request $request)
    {
        // $data = $request->validate([
        //     'avatar' => 'nullable|image|max:2048',
        //     'nome' => 'required|max:255',
        //     'descricao' => 'required',
        //     'telefone' => 'required',
        //     'cnpj' => 'required',
        // ]);

        $data = $request->all();

        $data['user_id'] = $request->user_id;
        // Processar o arquivo de avatar, se houver
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $path;
        }

        // Atualizar a empresa existente ou criar uma nova
        Empresa::updateOrCreate(
            ['uuid' => $data['uuid'] ?? ''],
            $data
        );


        return redirect()->route('empresa.configuracao')->with('success', 'Empresa atualizada ou criada com sucesso');
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
