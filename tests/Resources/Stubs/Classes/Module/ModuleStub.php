<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Resources\Stubs\Classes\Module;

/**
 * @see \Module
 */
abstract class ModuleStub
{
    /** @var bool */
    public static $forceReturnFalseOnRegisterHook = false;

    /**
     * @see \Module::registerHook()
     */
    public function registerHook($hook_name, $shop_list = null)
    {
        if (self::$forceReturnFalseOnRegisterHook) {
            return false;
        }

        return true;
    }
}
