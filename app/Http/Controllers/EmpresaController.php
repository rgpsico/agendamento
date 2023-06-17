<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\EmpresaGaleria;
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
        $data = $request->validate([
            'avatar' => 'nullable|image|max:2048',
            'nome' => 'required|max:255',
            'descricao' => 'required',
            'telefone' => 'required',
            'cnpj' => 'required',
            'endereco' => 'required',
            'cidade' =>  'required',
            'estado' => 'required',
            'uf' => 'required',
            'pais' => 'required',
            'valor_aula_de' => 'required',
            'valor_aula_ate' => 'required'

        ]);







        $data = $request->all();




        $data['user_id'] = $request->user_id;


        // Processar o arquivo de avatar, se houver 

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $path = public_path('/avatar');
            $file->move($path, $filename);
            $data['avatar'] = $filename;
        }



        // Atualizar a empresa existente ou criar uma nova
        $empresa = Empresa::updateOrCreate(
            ['user_id' => $data['user_id']],
            $data
        );

        $empresa->endereco()->updateOrCreate(
            ['empresa_id' => $empresa->id],
            [
                'cep' => $data['cep'],

                'endereco' => $data['endereco'] ?? 'End',
                'cidade' => $data['cidade'] ?? 'cit',
                'estado' => $data['estado'] ?? 'es',
                'uf' => $data['uf'],
                'pais' => $data['pais'],
            ]
        );


        return redirect()->route('empresa.configuracao', ['userId' => $request->user_id])->with('success', 'Empresa atualizada ou criada com sucesso');
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

    public function configuracao($userId)
    {
        $model = Empresa::where('user_id', $userId)->first();
        return view(
            'admin.empresas.treinoform',
            [
                'pageTitle' =>  'Configuração',
                'model' => $model
            ]
        );
    }

    public function fotos($userId)
    {
        $model = EmpresaGaleria::where('empresa_id', $userId)->get();
        return view(
            'admin.empresas.fotos',
            [
                'pageTitle' =>  'Fotos',
                'model' => $model
            ]
        );
    }


    public function uploadImage(Request $request, EmpresaGaleria $empresaGaleria)
    {

        $images = $request->file('image');

        // Limita a quantidade de fotos a 5
        if (count($images) > 5) {
            return back()->with('error', 'Você pode enviar no máximo 5 imagens.');
        }


        $numeroDeImagens = EmpresaGaleria::where('empresa_id', $request->empresa_id)->count();

        if ($numeroDeImagens >= 5) {
            return back()->with('error', 'O maximo de imagens que voce pode ter são cinco imagens');
        }

        if ($request->hasfile('image')) {

            foreach ($request->file('image') as $key => $image) {
                $name = time() . $key . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/galeria_escola');
                $image->move($destinationPath, $name);

                $empresaGaleria = new EmpresaGaleria();  // supondo que EmpresaGaleria é seu modelo para a galeria
                $empresaGaleria->image = $name;
                $empresaGaleria->empresa_id = $request->empresa_id;
                $empresaGaleria->save();
            }
        }


        return back()
            ->with('success', 'Image Enviada com sucesso');
    }

    public function destroy($id)
    {
        $image = EmpresaGaleria::find($id);

        if ($image) {
            // Remove o arquivo de imagem do servidor
            $imagePath = public_path('galeria_escola/' . $image->image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }

            // Exclui a imagem do banco de dados
            $image->delete();

            return back()->with('success', 'Imagem excluída com sucesso.');
        } else {
            return back()->with('error', 'Imagem não encontrada.');
        }
    }
}
