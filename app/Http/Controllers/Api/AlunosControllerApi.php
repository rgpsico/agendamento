<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\SendMassEmail;
use App\Models\Agendamento;
use App\Models\AlunoEndereco;
use App\Models\AlunoProfessor;
use App\Models\Alunos;
use App\Models\Modalidade;
use App\Models\Professor;
use App\Models\Usuario;
use App\Services\AsaasService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;


class AlunosControllerApi extends Controller
{
    // Mostrar todos os usuários

    protected $service;

    public function  __construct(AsaasService $service)
    {
        $this->service = $service;
    }


    public function index()
    {
        $users = Usuario::all();
        return response()->json($users);
    }

    public function byEmpresa($empresaId)
    {
        $alunos = Alunos::with('usuario')
            ->whereHas('professores', function ($query) use ($empresaId) {
                $query->where('empresa_id', $empresaId);
            })
            ->get();

        return response()->json($alunos);
    }

    public function treinoEmail()
    {
        // Mail::raw('Texto do e-mail', function ($message) {
        //     $message->to('rgyr2010@hotmail.com')->subject('Teste de e-mail');
        // });
        SendMassEmail::dispatch()->onQueue('emails');
    }
    // Criar um novo usuario
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:usuarios,email',
            'telefone' => 'required|string|max:20',
            'data_nascimento' => 'required|date',
            'password' => 'nullable|string|min:4',
        //    'professor_id' => 'nullable|integer|exists:professores,id',
            'endereco' => 'nullable|string|max:255',
            'cidade' => 'nullable|string|max:100|required_with:endereco,estado,cep',
            'estado' => 'nullable|string|max:2|required_with:endereco,cidade,cep',
            'cep' => 'nullable|string|max:10|required_with:endereco,cidade,estado',
        ]);

        return DB::transaction(function () use ($validated) {
            $usuario = Usuario::create([
                'nome' => $validated['nome'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password'] ?? '124'),
                'tipo_usuario' => 'Aluno',
                'data_nascimento' => $validated['data_nascimento'],
                'telefone' => $validated['telefone'],
            ]);

            $aluno = Alunos::create([
                'usuario_id' => $usuario->id,
            ]);

            if (!empty($validated['professor_id'])) {
                AlunoProfessor::firstOrCreate([
                    'aluno_id' => $aluno->id,
                    'professor_id' => $validated['professor_id'],
                ]);
            }

            if (!empty($validated['endereco'])) {
                AlunoEndereco::updateOrCreate(
                    ['aluno_id' => $aluno->id],
                    [
                        'endereco' => $validated['endereco'],
                        'cidade' => $validated['cidade'],
                        'estado' => $validated['estado'],
                        'cep' => $validated['cep'],
                    ]
                );
            }

            return response()->json([
                'usuario' => $usuario,
                'aluno' => $aluno,
            ], 201);
        });
    }

    // Mostrar um usuário específico
    public function show($id)
    {
        $user = Usuario::find($id);
        if ($user) {
            return response()->json($user);
        } else {
            return response()->json(['error' => 'Usuário não encontrado'], 404);
        }
    }


    public function updateEndereco($id, Request $request)
    {
        // Validate the request
        $request->validate([
            'cep' => 'required|string|max:10',
            'endereco' => 'required|string|max:255',
            'cidade' => 'required|string|max:100',
            'estado' => 'required|string|max:2',
            'pais' => 'required|string|max:100',
        ]);
        
        // Procura o aluno pelo usuario_id
        $aluno = Alunos::where('usuario_id', $id)->first();
        
        if (!$aluno) {
            return response()->json(['message' => 'Aluno não encontrado'], 404);
        }
        
        // IMPORTANTE: Use o $aluno->id, não o $id do parâmetro da função
        $endereco = AlunoEndereco::updateOrCreate(
            ['aluno_id' => $aluno->id], // Agora referenciando o ID real do aluno
            [
                'cep' => $request->cep,
                'endereco' => $request->endereco,
                'cidade' => $request->cidade,
                'estado' => $request->estado,
                'pais' => $request->pais,
            ]
        );
        
        return response()->json(['message' => 'Endereço atualizado com sucesso!']);
    }
    // Atualizar um usuário específico
    public function update($id, Request $request)
    {
        // Validação dos dados recebidos
        $request->validate([
            'primeiro_nome' => 'required|string|max:255',
            'data_nascimento' => 'required|date',
            'email' => 'required|email|max:255',
            'telefone' => 'required|string|max:15',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        // Encontra o usuário
        $usuario = Usuario::find($id);
        if (!$usuario) {
            return response()->json(['message' => 'Usuário não encontrado!'], 404);
        }
    
        // Encontra o aluno associado ao usuário
        $aluno = Alunos::where('usuario_id', $id)->first();
        if (!$aluno) {
            return response()->json(['message' => 'Aluno não encontrado!'], 404);
        }
    
        // Atualiza os dados do usuário
        $dataUpdateUsuario = [
            'nome' => $request->primeiro_nome . ' ' . ($request->ultimo_nome ?? ''),
            'email' => $request->email,
            'data_nascimento' => $request->data_nascimento,
            'telefone' => $request->telefone,
        ];
    
        // Atualiza os dados do usuário
        $usuario->update($dataUpdateUsuario);
    
        // Processa a imagem se foi enviada
        if ($request->hasFile('profile_image')) {
            // Verifica se já existe uma imagem e a exclui
            if ($aluno->avatar && Storage::exists('public/' . $aluno->avatar)) {
                Storage::delete('public/' . $aluno->avatar);
            }
    
            // Armazena a nova imagem
            $imagePath = $request->file('profile_image')->store('avatars', 'public');
    
            // Atualiza o avatar na tabela alunos
            $aluno->update(['avatar' => $imagePath]);
        }
    
        return response()->json(['message' => 'Dados atualizados com sucesso!']);
    }


    // Deletar um usuário específico
    public function destroy($id)
    {
        $user = Usuario::find($id);
        if ($user) {
            $user->delete();
            return response()->json(['success' => 'Usuário deletado com sucesso'], 200);
        } else {
            return response()->json(['error' => 'Usuário não encontrado'], 404);
        }
    }

    public function transacao(Request $request)
    {
        try {
            DB::transaction(function () use ($request) {

                $usuario = Usuario::create([
                    'nome' => $request->nome,
                    'email' => $request->email,
                    'password' => bcrypt($request->password),
                    'tipo_usuario' => $request->tipo_usuario,
                    'data_nascimento' => $request->data_nascimento,
                    'telefone' => $request->telefone
                ]);



                $modalidade = Modalidade::create([
                    'nome' => "SURF",

                ]);

                $alunos = Alunos::create([
                    'usuario_id' => $usuario->id,

                ]);

                $profesor = Professor::create([
                    'usuario_id' => $usuario->id,
                    'modalidade_id' => $modalidade->id,
                    'sobre' => 'TETE',
                    'avatar' => 'avatar'


                ]);

                // Criar um novo agendamento
                $agendamento = Agendamento::create([
                    'aluno_id' => $alunos->id, // Usando o id do usuário recém-criado
                    'modalidade_id' => $modalidade->id,
                    'professor_id' => $profesor->id,
                    'data_da_aula' => $request->data_da_aula,
                    'valor_aula' => $request->valor_aula
                ]);
            });
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function service(Request $request)
    {
        return  $this->service->cobranca($request);
    }

    public function getCobrancas()
    {
        return  $this->service->getCobrancas();
    }
}
