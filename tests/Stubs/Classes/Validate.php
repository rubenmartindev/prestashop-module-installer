<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Stubs\Classes;

class Validate
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
