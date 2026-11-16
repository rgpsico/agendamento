<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Perfil;
use App\Models\Professor;
use App\Models\Usuario;
use App\Support\EmpresasDoUsuario;
use App\Support\Modulos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * Usuários do estúdio (dono + funcionários).
 * - Dono e master definem as áreas que cada funcionário acessa (App\Support\Modulos).
 * - Papéis e permissões avançadas (globais do sistema) só o master mexe.
 * - Funcionário com a área "Usuários" cadastra colegas, mas não muda áreas nem mexe na conta do dono.
 * O acesso a {user} de outra empresa já é barrado pelo middleware "dono" (RegrasDeEmpresa).
 */
class UserManagementController extends Controller
{
    public function index()
    {
        $eu = auth()->user();
        $usuarios = Usuario::with(['perfis', 'professor', 'empresa'])->get();

        if (! $eu->isMasterUser()) {
            $empresas = EmpresasDoUsuario::ids($eu);

            $usuarios = $usuarios->filter(function ($usuario) use ($empresas) {
                $isDonoDaEmpresa = in_array((int) optional($usuario->empresa)->id, $empresas, true);
                $isProfessorDaEmpresa = in_array((int) optional($usuario->professor)->empresa_id, $empresas, true);
                $temPerfilNaEmpresa = $usuario->perfis->contains(function ($perfil) use ($empresas) {
                    $meta = json_decode($perfil->pivot->meta ?? '[]', true);

                    return in_array((int) ($meta['empresa_id'] ?? 0), $empresas, true);
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
            'areas' => $this->areasDe($user),
        ]);
    }

    /** Só master (rota com middleware "master"): papéis e permissões globais. */
    public function updatePermissions(Request $request, $id)
    {
        $user = Usuario::findOrFail($id);

        $user->syncRoles($request->roles ?? []);
        $user->syncPermissions($request->permissions ?? []);

        return response()->json(['success' => true]);
    }

    public function create()
    {
        return view('admin.usuarios.create', [
            'perfis'           => Perfil::all(),
            'permissions'      => Permission::where('name', 'not like', 'modulo.%')->get(),
            'podeDefinirAreas' => $this->podeDefinirAreas(),
            'ehMaster'         => auth()->user()->isMasterUser(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome'        => 'required|string|max:255',
            'email'       => 'required|email|unique:usuarios,email',
            'password'    => 'required|string|min:6',
            'perfis'      => 'array',
            'permissions' => 'array',
            'modulos'     => 'array',
            'modulos.*'   => 'in:' . implode(',', array_keys(Modulos::AREAS)),
        ]);

        $eu = auth()->user();
        $empresaId = EmpresasDoUsuario::ids($eu)[0] ?? null;
        abort_if(! $empresaId && ! $eu->isMasterUser(), 403, 'Empresa não identificada.');

        $usuario = DB::transaction(function () use ($request, $empresaId, $eu) {
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

            if ($eu->isMasterUser()) {
                foreach ((array) $request->perfis as $perfilNome) {
                    $perfil = Perfil::where('nome', $perfilNome)->first();
                    if ($perfil) {
                        $usuario->perfis()->attach($perfil->id, ['meta' => json_encode($empresaId ? ['empresa_id' => $empresaId] : [])]);
                    }
                }
                $usuario->syncPermissions(array_values(array_filter((array) $request->permissions, fn ($p) => ! str_starts_with($p, 'modulo.'))));
            }

            // Áreas liberadas: dono/master escolhe; funcionário cadastrando colega => padrão.
            $areas = $this->podeDefinirAreas() ? (array) $request->modulos : Modulos::PADRAO_FUNCIONARIO;
            $this->sincronizarAreas($usuario, $areas);

            return $usuario;
        });

        return redirect()
            ->route('admin.usuarios.index')
            ->with('success', "Usuário {$usuario->nome} criado com sucesso!");
    }

    public function edit($id)
    {
        $user = Usuario::with('perfis', 'roles', 'permissions')->findOrFail($id);
        $this->bloquearContaDoDono($user);

        return view('admin.usuarios.edit', [
            'user'             => $user,
            'roles'            => Role::all(),
            'permissions'      => Permission::where('name', 'not like', 'modulo.%')->get(),
            'perfis'           => Perfil::all(),
            'userRoles'        => $user->roles->pluck('name')->toArray(),
            'userPermissions'  => $user->getDirectPermissions()->pluck('name')->toArray(),
            'userPerfis'       => $user->perfis->pluck('id')->toArray(),
            'podeDefinirAreas' => $this->podeDefinirAreas() && $user->id !== auth()->id(),
            'areasMarcadas'    => $this->areasDe($user),
            'alvoAcessoTotal'  => Modulos::acessoTotal($user),
            'ehMaster'         => auth()->user()->isMasterUser(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = Usuario::findOrFail($id);
        $this->bloquearContaDoDono($user);

        $request->validate([
            'nome'        => 'required|string|max:255',
            'email'       => 'required|email|unique:usuarios,email,' . $user->id,
            'password'    => 'nullable|string|min:6|confirmed',
            'roles'       => 'array',
            'permissions' => 'array',
            'perfis'      => 'array',
            'modulos'     => 'array',
            'modulos.*'   => 'in:' . implode(',', array_keys(Modulos::AREAS)),
        ]);

        $eu = auth()->user();
        $podeTrocarSenha = $this->podeDefinirAreas() || $eu->can('alterar_senha') || $eu->id === $user->id;

        $user->update([
            'nome'     => $request->nome,
            'email'    => $request->email,
            'password' => ($request->password && $podeTrocarSenha) ? bcrypt($request->password) : $user->password,
        ]);

        if ($eu->isMasterUser()) {
            $user->syncRoles($request->roles ?? []);
            $user->perfis()->sync($request->perfis ?? []);
            // permissões avançadas; as de área ("modulo.*") são tratadas abaixo
            $avancadas = array_values(array_filter((array) $request->permissions, fn ($p) => ! str_starts_with($p, 'modulo.')));
            $user->syncPermissions(array_merge($avancadas, $user->getDirectPermissions()->pluck('name')
                ->filter(fn ($p) => str_starts_with($p, 'modulo.'))->all()));
        }

        // Ninguém muda as próprias áreas; só dono/master definem áreas de funcionário.
        if ($this->podeDefinirAreas() && $user->id !== $eu->id && ! Modulos::acessoTotal($user)) {
            $this->sincronizarAreas($user, (array) $request->modulos);
        }

        return redirect()
            ->route('admin.usuarios.index')
            ->with('success', 'Usuário atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $usuario = Usuario::findOrFail($id);
        $this->bloquearContaDoDono($usuario);
        abort_if($usuario->id === auth()->id(), 422, 'Você não pode excluir o seu próprio usuário.');

        $usuario->delete();

        if (request()->expectsJson()) {
            return response()->json(['message' => 'Usuário excluído com sucesso']);
        }

        return redirect()
            ->route('admin.usuarios.index')
            ->with('success', 'Usuário excluído com sucesso!');
    }

    /** Dono e master definem as áreas dos funcionários. */
    private function podeDefinirAreas(): bool
    {
        return Modulos::acessoTotal(auth()->user());
    }

    /** Funcionário não mexe na conta do dono/master (e-mail, senha, exclusão). */
    private function bloquearContaDoDono(Usuario $alvo): void
    {
        $eu = auth()->user();
        abort_if(
            $alvo->id !== $eu->id && Modulos::acessoTotal($alvo) && ! Modulos::acessoTotal($eu),
            403,
            'Só o responsável pelo estúdio pode alterar esta conta.'
        );
        abort_if($alvo->isMasterUser() && ! $eu->isMasterUser(), 403, 'Conta do administrador do sistema.');
    }

    /** @return string[] chaves das áreas liberadas para o usuário */
    private function areasDe(Usuario $usuario): array
    {
        if (Modulos::acessoTotal($usuario)) {
            return array_keys(Modulos::AREAS);
        }

        $nomes = $usuario->getDirectPermissions()->pluck('name')->all();

        return array_values(array_filter(array_keys(Modulos::AREAS), fn ($m) => in_array(Modulos::permissao($m), $nomes, true)));
    }

    /** Troca só as permissões "modulo.*" do usuário, mantendo as demais. */
    private function sincronizarAreas(Usuario $usuario, array $areas): void
    {
        $areas = array_values(array_intersect(array_keys(Modulos::AREAS), $areas));

        foreach (array_keys(Modulos::AREAS) as $modulo) {
            // Criadas pela migration 2026_11_16_000004 (que também libera o padrão aos funcionários atuais).
            // Não criamos aqui: criar só algumas ativaria o bloqueio sem os funcionários atuais terem o padrão.
            $permissao = Permission::where('name', Modulos::permissao($modulo))->where('guard_name', 'web')->first();
            if (! $permissao) {
                continue;
            }
            if (in_array($modulo, $areas, true)) {
                $usuario->givePermissionTo($permissao);
            } elseif ($usuario->hasDirectPermission($permissao)) {
                $usuario->revokePermissionTo($permissao);
            }
        }
    }
}
