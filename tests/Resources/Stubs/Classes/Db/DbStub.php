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

    /** @var int|false */
    public static $value = 1;

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

    public function getValue($sql, $use_cache = true)
    {
        return self::$value;
    }
}
