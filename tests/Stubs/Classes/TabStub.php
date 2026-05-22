<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Stubs\Classes;

use PrestaShopCollection;

class TabStub extends \ObjectModel
{
    /** @var bool */
    public static $forceReturnFalseOnAdd = false;

    /** @var bool */
    public static $forceReturnFalseOnDelete = false;

    /** @var string */
    public $class_name;

    public static function getIdFromClassName($class_name)
    {
        return 1;
    }

    public static function getCollectionFromModule($module, $id_lang = null)
    {
        $collection = new PrestaShopCollection('Tab');

        $tab1 = new static();
        $tab1->id           = 1;
        $tab1->class_name   = 'AdminMyModule1';

        $tab2 = new static();
        $tab2->id           = 2;
        $tab2->class_name   = 'AdminMyModule2';

        $collection->elements = [$tab1, $tab2];

        return $collection;
    }

    public function add($autoDate = true, $nullValues = false)
    {
        if (self::$forceReturnFalseOnAdd) {
            return false;
        }

        return true;
    }

    public function delete()
    {
        if (self::$forceReturnFalseOnDelete) {
            return false;
        }

        return true;
    }
}
