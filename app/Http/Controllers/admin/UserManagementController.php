<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
        $usuarios = Usuario::with('roles', 'permissions')->get();
        return view('admin.usuarios.index', compact('usuarios'));
    }

    public function getPermissions(User $user)
    {
        return response()->json([
            'roles' => $user->roles,
            'directPermissions' => $user->permissions
        ]);
    }
    
    public function updatePermissions(Request $request, User $user)
    {
        // Sincronizar roles
        $user->syncRoles($request->roles ?? []);
        
        // Sincronizar permissões diretas
        $user->syncPermissions($request->permissions ?? []);
        
        return response()->json(['success' => true]);
    }

    public function create()
    {
        $roles = Role::all();
        $permissions = Permission::all();
        return view('admin.usuarios.create', compact('roles', 'permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios,email',
            'password' => 'required|string|min:6',
            'roles' => 'array',
            'permissions' => 'array',
        ]);

        $usuario = Usuario::create([
            'nome' => $request->nome,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $usuario->syncRoles($request->roles ?? []);
        $usuario->syncPermissions($request->permissions ?? []);

        return redirect()->route('admin.usuarios.index')->with('success', 'Usuário criado com sucesso!');
    }

    public function edit(Usuario $user)
    {
       
        $roles = Role::all();
        $permissions = Permission::all();
        
        return view('admin.usuarios.edit', [
            'user' => $user,
            'roles' => $roles,
            'permissions' => $permissions,
            'userRoles' => $user->roles->pluck('name')->toArray(),
            'userPermissions' => $user->getDirectPermissions()->pluck('name')->toArray()
        ]);
    }

    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);

        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios,email,' . $id,
            'password' => 'nullable|string|min:6',
            'roles' => 'array',
            'permissions' => 'array',
        ]);

        $usuario->update([
            'nome' => $request->nome,
            'email' => $request->email,
            'password' => $request->password ? bcrypt($request->password) : $usuario->password,
        ]);

        $usuario->syncRoles($request->roles ?? []);
        $usuario->syncPermissions($request->permissions ?? []);

        return redirect()->route('admin.usuarios.index')->with('success', 'Usuário atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->delete();
        return response()->json(['message' => 'Usuário excluído com sucesso']);
    }
}