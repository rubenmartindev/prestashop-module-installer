<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Resources\Stubs\Classes;

/**
 * @see \ObjectModel
 */
abstract class ObjectModelStub
{
    /** @var bool */
    public static $forceReturnFalseOnAdd = false;

    /** @var bool */
    public static $forceReturnFalseOnUpdate = false;

    /** @var bool */
    public static $forceReturnFalseOnSave = false;

    /** @var bool */
    public static $forceReturnFalseOnDelete = false;

    /** @var int */
    public $id;

    /**
     * @see \ObjectModel::add()
     */
    public function add($autodate = true, $null_values = false)
    {
        if (self::$forceReturnFalseOnAdd) {
            return false;
        }

        return true;
    }

    /**
     * @see \ObjectModel::update()
     */
    public function update($null_values = false)
    {
        if (self::$forceReturnFalseOnUpdate) {
            return false;
        }

        return true;
    }

    /**
     * @see \ObjectModel::save()
     */
    public function save($null_values = false, $auto_date = true)
    {
        if (self::$forceReturnFalseOnSave) {
            return false;
        }

        return true;
    }

    /**
     * @see \ObjectModel::delete()
     */
    public function delete()
    {
        if (self::$forceReturnFalseOnDelete) {
            return false;
        }

        return true;
    }
}
