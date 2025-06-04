<?php


namespace App\Helpers;

use App\Models\PaymentConfiguration;

class PaymentConfigHelper
{
    public static function getConfig($empresaId, $method = null)
    {
        $config = PaymentConfiguration::getForEmpresa($empresaId);

        if ($method) {
            return $config->{$method . '_config'} ?? [];
        }

        return $config;
    }

    public static function isMethodEnabled($empresaId, $method)
    {
        $config = PaymentConfiguration::getForEmpresa($empresaId);

        return $config->{$method . '_enabled'} ?? false;
    }

    public static function getEnabledMethods($empresaId)
    {
        $config = PaymentConfiguration::getForEmpresa($empresaId);

        return $config->getEnabledPaymentMethods();
    }
}
