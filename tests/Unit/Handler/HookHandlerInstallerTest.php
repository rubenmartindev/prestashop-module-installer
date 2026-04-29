<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Unit\Handler;

use Module;
use PHPUnit_Framework_MockObject_MockObject;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Exception\HookHandlerInstallerException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\HookHandlerInstaller;

class HookHandlerInstallerTest extends AbstractHandlerInstallerTestCase
{
    public function testInstallReturnsTrueWhenWithoutHooks()
    {
        $handler = new HookHandlerInstaller($this->module, []);

        $this->assertTrue($handler->install());
    }

    public function testInstallReturnsTrueWhenWithHooks()
    {
        $handler = new HookHandlerInstaller($this->module, ['displayHeader', 'displayFooter']);

        $this->assertTrue($handler->install());
    }

    public function testInstallThrowsExceptionWhenRegisteringHookFails()
    {
        /** @var Module|PHPUnit_Framework_MockObject_MockObject */
        $this->module = $this->getMockBuilder(Module::class)
            ->setMethods(['registerHook'])
            ->getMockForAbstractClass()
        ;

        $this->module->method('registerHook')->willReturn(false);

        $this->expectException(HookHandlerInstallerException::class);

        $handler = new HookHandlerInstaller($this->module, ['returnFalse']);

        $handler->install();
    }

    public function testUninstallReturnsTrueWhenWithoutHooks()
    {
        $handler = new HookHandlerInstaller($this->module, []);

        $this->assertTrue($handler->uninstall());
    }

    public function testUninstallReturnsTrueWhenWithHooks()
    {
        $handler = new HookHandlerInstaller($this->module, ['displayHeader', 'displayFooter']);

        $this->assertTrue($handler->uninstall());
    }
}
