<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Resources;

use Module;
use PHPUnit_Framework_MockObject_MockObject;

trait ModuleTrait
{
    /**
     * @param string[] $methods
     *
     * @return Module|PHPUnit_Framework_MockObject_MockObject
     */
    protected function getModule($methods = [])
    {
        $module = $this->getMockForAbstractClass(
            Module::class,
            [],
            '',
            true,
            true,
            true,
            $methods
        );

        $module->name = 'mymodule';

        return $module;
    }
}
