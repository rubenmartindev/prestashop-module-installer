<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Stubs\Classes;

class Configuration
{
    const CONFIGURATION = [
        'PS_LANG_DEFAULT' => 1,
    ];

    public static function get($key, $idLang = null, $idShopGroup = null, $idShop = null, $default = false)
    {
        if (\array_key_exists($key, self::CONFIGURATION)) {
            return self::CONFIGURATION[$key];
        }

        return false;
    }
}
