<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Resources\Stubs\Classes;

use PrestaShopCollection;

/**
 * @see \Tab
 *
 * @property bool $forceReturnFalseOnAdd
 * @property bool $forceReturnFalseOnSave
 * @property bool $forceReturnFalseOnUpdate
 * @property bool $forceReturnFalseOnDelete
 */
class TabStub extends \ObjectModel
{
    /** @see \Tab::$class_name */
    public $class_name;

    /** @see \Tab::$module */
    public $module;

    /** @see \Tab::$definition */
    public static $definition = [
        'table'     => 'tab',
        'primary'   => 'id_tab',
    ];

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
