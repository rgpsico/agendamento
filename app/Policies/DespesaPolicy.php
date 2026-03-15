<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Despesas;
use App\Models\Usuario;

class DespesaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Usuario $usuario): bool
    {
        dd('aaa');
        return $usuario->id === $despesas->usuario_id || $usuario->isAdmin();  // Exemplo
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Usuario $usuario, Despesas $despesa): bool
    {
        return $usuario->id === $despesa->usuario_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Usuario $usuario): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Usuario $usuario, Despesas $despesa): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Usuario $usuario, Despesas $despesa): bool
    {
       return $usuario->id === $despesa->usuario_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Usuario $usuario, Despesas $despesa): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Usuario $usuario, Despesas $despesa): bool
    {
        //
    }
}
