<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Agendamento;
use App\Models\Alunos;
use App\Models\Modalidade;
use App\Models\Professor;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;
use Google_Client;

class UserControllerApi extends Controller
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



    public function googleAuth(Request $request)
    {

        $client = new Google_Client(['client_id' => env('GOOGLE_CLIENT_ID')]);
        $payload = $client->verifyIdToken($request->token);

        if (!$payload) {
            return response()->json(['error' => 'Invalid token'], 401);
        }

        $googleUserId = $payload['sub'];

        // Você pode buscar ou criar um usuário com base no ID do Google
        // Aqui você também pode verificar outros campos, como o e-mail do usuário.
        $user = Usuario::firstOrCreate(['google_id' => $googleUserId], [
            'name' => $payload['name'],
            'email' => $payload['email'],
            // outros campos que você desejar
        ]);

        // Aqui você pode autenticar o usuário e criar um token de autenticação,
        // se você estiver usando, por exemplo, o `tymon/jwt-auth` para JWT.

        Auth::login($user);

        return response()->json(['message' => 'User authenticated']);
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
    public function update(Request $request, $id)
    {
        $user = Usuario::find($id);
        if ($user) {
            $user->update($request->all());
            return response()->json($user);
        } else {
            return response()->json(['error' => 'Usuário não encontrado'], 404);
        }
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
