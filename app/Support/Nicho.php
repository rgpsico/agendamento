<?php

namespace App\Support;

use App\Models\NichoConfiguracao;

/**
 * Atalhos para ler a configuração do nicho do domínio acessado.
 * Sem nicho no domínio (ou sem a palavra configurada) vale a palavra de sempre, então nada muda nos nichos antigos.
 */
class Nicho
{
    public static function atual(): ?NichoConfiguracao
    {
        return app()->has('currentNicho') ? app('currentNicho') : null;
    }

    public static function termo(string $chave, ?string $padrao = null): string
    {
        $nicho = self::atual();

        return $nicho ? $nicho->termo($chave, $padrao) : ($padrao ?? (NichoConfiguracao::TERMOS[$chave][0] ?? $chave));
    }

    public static function recurso(string $chave): bool
    {
        $nicho = self::atual();

        return $nicho ? $nicho->recurso($chave) : false;
    }
}
