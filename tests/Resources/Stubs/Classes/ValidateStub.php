<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Resources\Stubs\Classes;

/**
 * @see \Validate
 */
class ValidateStub
{
    /**
     * @see \Validate::isHookName()
     */
    public static function isHookName($hook)
    {
        return preg_match('/^[a-zA-Z0-9_-]+$/', $hook);
    }

    /**
     * @see \Validate::isTableOrIdentifier()
     */
    public static function isTableOrIdentifier($table)
    {
        return preg_match('/^[a-zA-Z0-9_-]+$/', $table);
    }
}
