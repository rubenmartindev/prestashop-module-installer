<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Handler;

use Module;
use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit\Framework\TestCase;

abstract class AbstractHandlerInstallerTestCase extends TestCase
{
    /** @var Module|PHPUnit_Framework_MockObject_MockObject */
    protected $module;

    public function setUp()
    {
        $this->module = $this->getMockForAbstractClass(Module::class);

        $this->module->name = 'mymodule';
    }
}
