<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Perfil;
use App\Models\Professor;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserManagementController extends Controller
{
    public function __construct()
    {
        // $this->middleware('permission:gerenciar_usuarios');
    }

    public function index()
    {
        $empresaId = auth()->user()->empresa->id ?? null;

        $usuarios = Usuario::with(['perfis', 'professor', 'empresa'])->get();

        if ($empresaId) {
            $usuarios = $usuarios->filter(function ($usuario) use ($empresaId) {
                $isDonoDaEmpresa = optional($usuario->empresa)->id === $empresaId;
                $isProfessorDaEmpresa = optional($usuario->professor)->empresa_id === $empresaId;
                $temPerfilNaEmpresa = $usuario->perfis->contains(function ($perfil) use ($empresaId) {
                    $meta = json_decode($perfil->pivot->meta ?? '[]', true);

                    return (int) ($meta['empresa_id'] ?? 0) === (int) $empresaId;
                });

                return $isDonoDaEmpresa || $isProfessorDaEmpresa || $temPerfilNaEmpresa;
            })->values();
        }

        return view('admin.usuarios.index', compact('usuarios'));
    }

    public function getPermissions($id)
    {
        $user = Usuario::findOrFail($id);

        return response()->json([
            'roles' => $user->roles,
            'directPermissions' => $user->permissions,
        ]);
    }

    public function updatePermissions(Request $request, $id)
    {
        $user = Usuario::findOrFail($id);

        $user->syncRoles($request->roles ?? []);
        $user->syncPermissions($request->permissions ?? []);

        return response()->json(['success' => true]);
    }

    public function create()
    {
        $perfis = Perfil::all();
        $permissions = Permission::all();

        return view('admin.usuarios.create', compact('perfis', 'permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome'        => 'required|string|max:255',
            'email'       => 'required|email|unique:usuarios,email',
            'password'    => 'required|string|min:6',
            'perfis'      => 'array',
            'permissions' => 'array',
        ]);

        $empresaId = auth()->user()->empresa->id ?? null;

        $usuario = Usuario::create([
            'nome'         => $request->nome,
            'email'        => $request->email,
            'password'     => bcrypt($request->password),
            'tipo_usuario' => 'Professor',
        ]);

        Professor::create([
            'usuario_id' => $usuario->id,
            'empresa_id' => $empresaId,
            'sobre'      => '',
            'avatar'     => '',
        ]);

        if ($request->perfis) {
            foreach ($request->perfis as $perfilNome) {
                $perfil = Perfil::where('nome', $perfilNome)->first();

                if (! $perfil) {
                    continue;
                }

                $meta = $empresaId ? ['empresa_id' => $empresaId] : [];

                $usuario->perfis()->attach($perfil->id, ['meta' => json_encode($meta)]);
            }
        }

        $usuario->syncPermissions($request->permissions ?? []);

        return redirect()
            ->route('admin.usuarios.index')
            ->with('success', 'Usuário criado com sucesso!');
    }

    public function edit($id)
    {
        $user = Usuario::with('perfis', 'roles', 'permissions')->findOrFail($id);
        $roles = Role::all();
        $permissions = Permission::all();
        $perfis = Perfil::all();

        return view('admin.usuarios.edit', [
            'user' => $user,
            'roles' => $roles,
            'permissions' => $permissions,
            'perfis' => $perfis,
            'userRoles' => $user->roles->pluck('name')->toArray(),
            'userPermissions' => $user->getDirectPermissions()->pluck('name')->toArray(),
            'userPerfis' => $user->perfis->pluck('id')->toArray(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = Usuario::findOrFail($id);

        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios,email,' . $user->id,
            'roles' => 'array',
            'permissions' => 'array',
            'perfis' => 'array',
        ]);

        $user->update([
            'nome' => $request->nome,
            'email' => $request->email,
            'password' => $request->password ? bcrypt($request->password) : $user->password,
        ]);

        $user->syncRoles($request->roles ?? []);
        $user->syncPermissions($request->permissions ?? []);
        $user->perfis()->sync($request->perfis ?? []);

        return redirect()
            ->route('admin.usuarios.index')
            ->with('success', 'Usuário atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->delete();

        if (request()->expectsJson()) {
            return response()->json(['message' => 'Usuário excluído com sucesso']);
        }

        return redirect()
            ->route('admin.usuarios.index')
            ->with('success', 'Usuário excluído com sucesso!');
    }
}
