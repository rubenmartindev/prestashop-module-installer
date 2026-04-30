<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Stubs\Classes\Module;

abstract class Module
{
    /** @var bool */
    public static $forceReturnFalseOnRegisterHook = false;

    public function registerHook($hook_name, $shop_list = null)
    {
        if (self::$forceReturnFalseOnRegisterHook) {
            return false;
        }

        return true;
    }
}
