<?php

namespace App\Policies;

use App\Models\Tarefa;
use App\Models\Usuario;

class TarefaPolicy
{
    public function create(Usuario $usuario): bool
    {
        return (bool) $usuario->empresa;
    }

    public function update(Usuario $usuario, Tarefa $tarefa): bool
    {
        return $usuario->empresa && (int) $tarefa->tenant_id === (int) $usuario->empresa->id;
    }
}
