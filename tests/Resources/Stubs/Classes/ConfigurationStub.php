<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Resources\Stubs\Classes;

/**
 * @see \Configuration
 */
class ConfigurationStub
{
    const CONFIGURATION = [
        'PS_LANG_DEFAULT' => 1,
    ];

    /**
     * @see \Language::get()
     */
    public static function get($key, $idLang = null, $idShopGroup = null, $idShop = null, $default = false)
    {
        if (\array_key_exists($key, self::CONFIGURATION)) {
            return self::CONFIGURATION[$key];
        }

        return false;
    }
}
