<?php

namespace App\Support;

use App\Models\Empresa;
use App\Models\Professor;
use App\Models\Usuario;

/** Empresas a que um usuario pertence: as que ele possui + a empresa em que ele e professor. */
class EmpresasDoUsuario
{
    /** @return int[] */
    public static function ids(Usuario $usuario): array
    {
        $ids = Empresa::where('user_id', $usuario->id)->pluck('id')->all();

        if ($empresaDoProfessor = Professor::where('usuario_id', $usuario->id)->value('empresa_id')) {
            $ids[] = (int) $empresaDoProfessor;
        }

        return array_values(array_unique(array_map('intval', $ids)));
    }
}
