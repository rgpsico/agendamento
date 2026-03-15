<?php 


namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::all();
        return view('admin.roles.create', compact('permissions'));
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id); // pega a role que você quer editar
        $permissions = Permission::all(); // todas as permissões

        // retorna para a view de edição (pode ser a mesma da criação)
        return view('admin.roles.edit', compact('role', 'permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'array',
        ]);

        $role = Role::create(['name' => $request->name]);
        if($request->permissions) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('admin.roles.index')->with('success', 'Role criada com sucesso!');
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
            'permissions' => 'array',
        ]);

        $role->update(['name' => $request->name]);

        if($request->permissions) {
            $role->syncPermissions($request->permissions);
        } else {
            $role->syncPermissions([]); // remove todas se nenhuma for selecionada
        }

        return redirect()->route('admin.roles.index')->with('success', 'Role atualizada com sucesso!');
    }

}
