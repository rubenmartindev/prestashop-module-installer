<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Resources\Stubs\Classes\Db;

use PrestaShopDatabaseException;

/**
 * @see \Db
 */
class DbStub
{
    /** @var bool */
    public static $forceThrowExceptionOnExecute = false;

    /**
     * @see \Db::getInstance()
     */
    public static function getInstance($master = true)
    {
        $instance = new self();

        return $instance;
    }

    /**
     * @\Db::execute()
     */
    public function execute($sql, $use_cache = true)
    {
        if (self::$forceThrowExceptionOnExecute) {
            throw new PrestaShopDatabaseException();
        }

        return true;
    }
}
