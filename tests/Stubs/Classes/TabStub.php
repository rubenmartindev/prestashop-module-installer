<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Stubs\Classes;

use ObjectModel;
use PrestaShopCollection;

class TabStub extends ObjectModel
{
    /** @var string */
    public $class_name;

    public static function getIdFromClassName($class_name)
    {
        return 1;
    }

    public static function getCollectionFromModule($module, $id_lang = null)
    {
        return new PrestaShopCollection('Tab');
    }
}
