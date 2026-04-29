<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Unit\Handler;

use Module;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

abstract class AbstractHandlerInstallerTestCase extends TestCase
{
    /** @var Module|PHPUnit_Framework_MockObject_MockObject */
    protected $module;

    public function setUp()
    {
        $this->module = $this->getMockForAbstractClass(Module::class);
    }
}
