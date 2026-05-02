<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Stubs\Classes;

class Validate
{
    public static function isHookName($hook)
    {
        return preg_match('/^[a-zA-Z0-9_-]+$/', $hook);
    }

    public static function isTablePrefix($data)
    {
        return preg_match('/^[a-z0-9_]+$/ui', $data);
    }
}
