<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Unit\Handler;

use RubenMartinDev\PrestaShopModuleInstaller\Handler\Exception\HookHandlerInstallerException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\HookHandlerInstaller;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Stubs\Classes\Module\Module;

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
        $this->expectException(HookHandlerInstallerException::class);

        Module::$forceReturnFalseOnRegisterHook = true;

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
