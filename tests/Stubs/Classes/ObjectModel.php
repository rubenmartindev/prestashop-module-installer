<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Stubs\Classes;

abstract class ObjectModel
{
    /** @var bool */
    public static $forceReturnFalseOnAdd = false;

    /** @var bool */
    public static $forceReturnFalseOnUpdate = false;

    /** @var bool */
    public static $forceReturnFalseOnDelete = false;

    /** @var int */
    public $id;

    public function add($autodate = true, $null_values = false)
    {
        if (self::$forceReturnFalseOnAdd) {
            return false;
        }

        return true;
    }

    public function update($null_values = false)
    {
        if (self::$forceReturnFalseOnUpdate) {
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
