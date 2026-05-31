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

    /** @var bool */
    public static $forceReturnFalseOnUpdateValue = false;

    /** @var bool */
    public static $forceReturnFalseOnDeleteByName = false;

    /**
     * @see \Configuration::get()
     */
    public static function get($key, $idLang = null, $idShopGroup = null, $idShop = null, $default = false)
    {
        if (\array_key_exists($key, self::CONFIGURATION)) {
            return self::CONFIGURATION[$key];
        }

        return false;
    }

    /**
     * @see \Configuration::updateValue()
     */
    public static function updateValue($key, $values, $html = false, $id_shop_group = null, $id_shop = null)
    {
        if (self::$forceReturnFalseOnUpdateValue) {
            return false;
        }

        return true;
    }

    /**
     * @see \Configuration::deleteByName()
     */
    public static function deleteByName($key)
    {
        if (self::$forceReturnFalseOnDeleteByName) {
            return false;
        }

        return true;
    }
}
