<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Agendamento;
use App\Models\AlunoEndereco;
use App\Models\Alunos;
use App\Models\Modalidade;
use App\Models\Professor;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AlunosControllerApi extends Controller
{
    // Mostrar todos os usuários
    public function index()
    {
        $users = Usuario::all();
        return response()->json($users);
    }

    // Criar um novo usuário
    public function store(Request $request)
    {
        $user = Usuario::create($request->all());
        return response()->json($user, 201);
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

    // Atualizar um usuário específico
    public function update($id, Request $request)
    {
        // Validação dos dados recebidos
        $request->validate([
            'primeiro_nome' => 'required|string|max:255',
            //  'ultimo_nome' => 'required|string|max:255',
            'data_nascimento' => 'required|date',
            'email' => 'required|email|max:255',
            'telefone' => 'required|string|max:15',
            // 'endereco' => 'required|string|max:255',
            // 'cidade' => 'required|string|max:100',
            // 'estado' => 'required|string|max:100',
            // 'cep' => 'required|string|max:10',
            // 'pais' => 'required|string|max:100'
        ]);

        // Atualiza os dados no modelo Usuario
        $usuario = Usuario::find($id);
        if (!$usuario) {
            return response()->json(['message' => 'Aluno não encontrado!'], 404);
        }

        $usuario->update([
            'nome' => $request->primeiro_nome . ' ' . $request->ultimo_nome,
            'email' => $request->email,
            'data_nascimento' => $request->data_nascimento,
            'telefone' => $request->telefone,
        ]);

        // Atualiza ou cria o endereço no modelo AlunoEndereco
        // $endereco = AlunoEndereco::firstOrNew(['aluno_id' => $id]);
        // $endereco->update([
        //     'endereco' => $request->endereco,
        //     'cidade' => $request->cidade,
        //     'estado' => $request->estado,
        //     'cep' => $request->cep,
        // ]);

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
}
