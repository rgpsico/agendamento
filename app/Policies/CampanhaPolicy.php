<?php

namespace App\Policies;

use App\Models\Campanha;
use App\Models\Usuario;

class CampanhaPolicy
{
    public function viewAny(Usuario $usuario): bool
    {
        return (bool) $usuario->empresa;
    }

    public function view(Usuario $usuario, Campanha $campanha): bool
    {
        return $this->sameTenant($usuario, $campanha);
    }

    public function create(Usuario $usuario): bool
    {
        return (bool) $usuario->empresa;
    }

    public function update(Usuario $usuario, Campanha $campanha): bool
    {
        return $this->sameTenant($usuario, $campanha);
    }

    public function delete(Usuario $usuario, Campanha $campanha): bool
    {
        return $this->sameTenant($usuario, $campanha);
    }

    private function sameTenant(Usuario $usuario, Campanha $campanha): bool
    {
        return $usuario->empresa && (int) $campanha->tenant_id === (int) $usuario->empresa->id;
    }
}
