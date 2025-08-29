<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Perfil;
use App\Models\Usuario;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function __construct()
    {
        // $this->middleware('permission:gerenciar_usuarios');
    }

    public function index()
{
    $usuarios = Usuario::with('perfis')->get(); // mudou roles -> perfis
    return view('admin.usuarios.index', compact('usuarios'));
}


    public function getPermissions(Usuario $user)
    {
        return response()->json([
            'roles' => $user->roles,
            'directPermissions' => $user->permissions
        ]);
    }
    
    public function updatePermissions(Request $request, Usuario $user)
    {
        // Sincronizar roles
        $user->syncRoles($request->roles ?? []);
        
        // Sincronizar permissões diretas
        $user->syncPermissions($request->permissions ?? []);
        
        return response()->json(['success' => true]);
    }

    public function create()
    {
        $perfis = Perfil::all();          // Pega todos os perfis cadastrados
        $permissions = Permission::all(); // Mantém as permissões diretas
        return view('admin.usuarios.create', compact('perfis', 'permissions'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios,email',
            'password' => 'required|string|min:6',
            'perfis' => 'array', // substitui 'roles'
            'permissions' => 'array',
            'empresa_id' => 'nullable|exists:empresa,id',
        ]);

        // Cria usuário base
        $usuario = Usuario::create([
            'nome' => $request->nome,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'tipo_usuario' => 'Professor', // ou outro valor padrão
        ]);

        // Vincula perfis com meta
        if ($request->perfis) {
            foreach ($request->perfis as $perfilNome) {
                $perfil = Perfil::where('nome', $perfilNome)->first();
                if (!$perfil) continue;

                $meta = [];
                if ($perfilNome === 'professor' && $request->empresa_id) {
                    $meta['empresa_id'] = $request->empresa_id;
                }

                $usuario->perfis()->attach($perfil->id, ['meta' => json_encode($meta)]);
            }
        }

        // Vincula permissões diretas
        $usuario->syncPermissions($request->permissions ?? []);

        return redirect()->route('admin.usuarios.index')
                        ->with('success', 'Usuário criado com sucesso!');
    }


    public function edit(Usuario $user)
    {
        // Pega todos os roles, permissões e perfis
        $roles = Role::all();
        $permissions = Permission::all();
        $perfis = Perfil::all(); // <-- trazer todos os perfis

        return view('admin.usuarios.edit', [
            'user' => $user,
            'roles' => $roles,
            'permissions' => $permissions,
            'perfis' => $perfis, // <-- enviar para a view
            'userRoles' => $user->roles->pluck('name')->toArray(),
            'userPermissions' => $user->getDirectPermissions()->pluck('name')->toArray(),
            'userPerfis' => $user->perfis->pluck('id')->toArray() // <-- ids dos perfis que o usuário já possui
        ]);
    }


   public function update(Request $request, Usuario $user)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios,email,' . $user->id,
         //   'password' => 'nullable|string|min:6|confirmed',
            'roles' => 'array',
            'permissions' => 'array',
            'perfis' => 'array'
        ]);

        $user->update([
            'nome' => $request->nome,
            'email' => $request->email,
            'password' => $request->password ? bcrypt($request->password) : $user->password,
        ]);

        // Atualizar roles e permissões
        $user->syncRoles($request->roles ?? []);
        $user->syncPermissions($request->permissions ?? []);

        // Atualizar perfis
        $user->perfis()->sync($request->perfis ?? []);

        return redirect()->route('admin.usuarios.index')->with('success', 'Usuário atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->delete();
        return response()->json(['message' => 'Usuário excluído com sucesso']);
    }
}