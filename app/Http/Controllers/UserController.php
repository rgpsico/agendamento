<?php 

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    public function index()
    {
        $usuarios = User::with(['roles', 'permissions'])->get();
        $roles = Role::all();
        $permissions = Permission::all();
        
        return view('admin.usuario.index', compact('usuarios', 'roles', 'permissions'));
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
    
    // Outros métodos (create, store, edit, update, destroy)...
}