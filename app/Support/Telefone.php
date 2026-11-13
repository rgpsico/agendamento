<?php

namespace App\Support;

/**
 * Celular guardado só com dígitos, sem o 55 do Brasil: "(21) 99999-9999" e "+55 21 99999 9999" viram o mesmo número.
 */
class Telefone
{
    public static function normalizar($valor): string
    {
        $digitos = preg_replace('/\D+/', '', (string) $valor);

        if (in_array(strlen($digitos), [12, 13], true) && strpos($digitos, '55') === 0) {
            $digitos = substr($digitos, 2);
        }

        return ltrim($digitos, '0') === '' ? '' : $digitos;
    }

    /** E-mail fictício (".invalid" nunca existe) só para preencher a coluna obrigatória de quem entra só com celular. */
    public static function emailFicticio(string $telefone): string
    {
        return "{$telefone}@celular.invalid";
    }
}
