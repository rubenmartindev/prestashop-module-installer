<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Resources\Stubs\Classes;

use ObjectModel;
use PrestaShopCollection;

/**
 * @see \Tab
 */
class TabStub extends ObjectModel
{
    /** @var string */
    public $class_name;

    /**
     * @see \Tab::getIdFromClassName()
     */
    public static function getIdFromClassName($class_name)
    {
        return 1;
    }

    /**
     * @see \Tab::getCollectionFromModule()
     */
    public static function getCollectionFromModule($module, $id_lang = null)
    {
        return new PrestaShopCollection('Tab');
    }
}
