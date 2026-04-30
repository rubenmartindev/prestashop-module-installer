<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Stubs\Classes\Db;

use RubenMartinDev\PrestaShopModuleInstaller\Tests\Stubs\Classes\Exception\PrestaShopDatabaseException;

class Db
{
    /** @var bool */
    public static $forceThrowExceptionOnExecute = false;

    public static function getInstance($master = true)
    {
        $instance = new self();

        return $instance;
    }

    public function execute($sql, $use_cache = true)
    {
        if (self::$forceThrowExceptionOnExecute) {
            throw new PrestaShopDatabaseException();
        }

        return true;
    }
}
