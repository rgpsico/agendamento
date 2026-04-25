<?php

namespace App\Policies;

use App\Models\Lead;
use App\Models\Usuario;

class LeadPolicy
{
    public function viewAny(Usuario $usuario): bool
    {
        return $this->hasTenant($usuario);
    }

    public function view(Usuario $usuario, Lead $lead): bool
    {
        return $this->sameTenant($usuario, $lead);
    }

    public function create(Usuario $usuario): bool
    {
        return $this->hasTenant($usuario);
    }

    public function update(Usuario $usuario, Lead $lead): bool
    {
        return $this->sameTenant($usuario, $lead);
    }

    public function move(Usuario $usuario, Lead $lead): bool
    {
        return $this->sameTenant($usuario, $lead);
    }

    public function delete(Usuario $usuario, Lead $lead): bool
    {
        return $this->sameTenant($usuario, $lead) && ($usuario->can('crm.leads.delete') || $usuario->hasRole('admin') || $usuario->isMasterUser());
    }

    public function viewReports(Usuario $usuario): bool
    {
        return $this->hasTenant($usuario) && ($usuario->can('crm.reports.view') || $usuario->hasAnyRole(['admin', 'manager']) || $usuario->isMasterUser());
    }

    private function sameTenant(Usuario $usuario, Lead $lead): bool
    {
        return $this->hasTenant($usuario) && (int) $lead->tenant_id === (int) $usuario->empresa->id;
    }

    private function hasTenant(Usuario $usuario): bool
    {
        return (bool) $usuario->empresa;
    }
}
