<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Unit\Handler\Hook;

use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\Exception\FailedRegisterHookException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\HookHandlerInstaller;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\Item\HookItem;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Stubs\Classes\Module\Module;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Unit\Handler\AbstractHandlerInstallerTestCase;

class HookHandlerInstallerTest extends AbstractHandlerInstallerTestCase
{
    public function testAddHandler()
    {
        $handler = new HookHandlerInstaller($this->module);

        $hookItem = HookItem::create('displayHeader');

        $handler->addHook($hookItem);

        $this->assertSame($hookItem, $handler->getHook('displayHeader'));
    }

    public function testGetHookReturnsNullWhenHookNotFound()
    {
        $handler = new HookHandlerInstaller($this->module);

        $this->assertNull($handler->getHook('nonExistingHook'));
    }

    public function testGetHookReturnsHookItemWhenFound()
    {
        $handler = new HookHandlerInstaller($this->module);

        $hookItem = HookItem::create('displayHeader');

        $handler->addHook($hookItem);

        $this->assertSame($hookItem, $handler->getHook('displayHeader'));
    }

    public function testRemoveHook()
    {
        $handler = new HookHandlerInstaller($this->module);

        $hookItem = HookItem::create('displayHeader');

        $handler->addHook($hookItem);

        $handler->removeHook('displayHeader');

        $this->assertNull($handler->getHook('displayHeader'));
    }

    public function testGetHooks()
    {
        $handler = new HookHandlerInstaller($this->module);

        $hookItem1 = HookItem::create('displayHeader');
        $hookItem2 = HookItem::create('displayFooter');

        $handler->addHook($hookItem1);
        $handler->addHook($hookItem2);

        $hooks = $handler->getHooks();

        $this->assertCount(2, $hooks);
        $this->assertSame($hookItem1, $hooks[0]);
        $this->assertSame($hookItem2, $hooks[1]);
    }

    public function testInstallReturnsTrueWhenWithoutHooks()
    {
        $handler = new HookHandlerInstaller($this->module);

        $this->assertTrue($handler->install());
    }

    public function testInstallReturnsTrueWhenWithHooks()
    {
        $handler = new HookHandlerInstaller($this->module, [
            HookItem::create('displayHeader'),
            HookItem::create('displayFooter')
        ]);

        $this->assertTrue($handler->install());
    }

    public function testInstallThrowsExceptionWhenRegisteringHookFails()
    {
        $this->expectException(FailedRegisterHookException::class);

        Module::$forceReturnFalseOnRegisterHook = true;

        $handler = new HookHandlerInstaller($this->module, [
            HookItem::create('displayHeader')
        ]);

        $handler->install();
    }

    public function testUninstallReturnsTrueWhenWithoutHooks()
    {
        $handler = new HookHandlerInstaller($this->module, []);

        $this->assertTrue($handler->uninstall());
    }

    public function testUninstallReturnsTrueWhenWithHooks()
    {
        $handler = new HookHandlerInstaller($this->module, [
            HookItem::create('displayHeader'),
            HookItem::create('displayFooter')
        ]);

        $this->assertTrue($handler->uninstall());
    }
}
