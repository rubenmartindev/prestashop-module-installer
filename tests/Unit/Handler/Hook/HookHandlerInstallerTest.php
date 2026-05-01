<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Unit\Handler\Hook;

use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\Exception\FailedRegisterHookException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\Exception\HooksIsEmptyException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\Exception\HooksMustBeInstanceOfHookItemException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\HookHandlerInstaller;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\Item\HookItem;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Stubs\Classes\Module\Module;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Unit\Handler\AbstractHandlerInstallerTestCase;

class HookHandlerInstallerTest extends AbstractHandlerInstallerTestCase
{
    public function testConstructThrowsExceptionWhenEmptyHooks()
    {
        $this->expectException(HooksIsEmptyException::class);

        new HookHandlerInstaller($this->module, []);
    }

    public function testConstructThrowsExceptionWhenInvalidHooks()
    {
        $this->expectException(HooksMustBeInstanceOfHookItemException::class);

        new HookHandlerInstaller($this->module, [
            'invalidHook',
        ]);
    }

    public function testConstruct()
    {
        $hookItem1 = new HookItem('displayHeader');
        $hookItem2 = new HookItem('displayFooter');

        $handler = new HookHandlerInstaller($this->module, [
            $hookItem1,
            $hookItem2,
        ]);

        $this->assertCount(2, $handler->getHooks());
        $this->assertSame($hookItem1, $handler->getHook('displayHeader'));
        $this->assertSame($hookItem2, $handler->getHook('displayFooter'));
    }

    public function testAddHook()
    {
        $hookItem1 = new HookItem('displayHeader');
        $hookItem2 = new HookItem('displayFooter');

        $handler = new HookHandlerInstaller($this->module, [
            $hookItem1
        ]);

        $handler->addHook($hookItem2);

        $this->assertSame($hookItem1, $handler->getHook('displayHeader'));
        $this->assertSame($hookItem2, $handler->getHook('displayFooter'));
    }

    public function testGetHookReturnsNullWhenHookNotFound()
    {
        $handler = new HookHandlerInstaller($this->module, [
            new HookItem('displayHeader'),
        ]);

        $this->assertNull($handler->getHook('nonExistingHook'));
    }

    public function testGetHookReturnsHookItemWhenFound()
    {
        $hookItem1 = new HookItem('displayHeader');
        $hookItem2 = new HookItem('displayFooter');

        $handler = new HookHandlerInstaller($this->module, [
            $hookItem1
        ]);

        $handler->addHook($hookItem2);

        $this->assertSame($hookItem1, $handler->getHook('displayHeader'));
        $this->assertSame($hookItem2, $handler->getHook('displayFooter'));
    }

    public function testRemoveHook()
    {
        $hookItem1 = new HookItem('displayHeader');
        $hookItem2 = new HookItem('displayFooter');
        $hookItem3 = new HookItem('displaySidebar');

        $handler = new HookHandlerInstaller($this->module, [
            $hookItem1,
            $hookItem2,
        ]);

        $handler->addHook($hookItem3);

        $handler->removeHook('displayHeader');
        $handler->removeHook('displayFooter');

        $this->assertNull($handler->getHook('displayHeader'));
        $this->assertNull($handler->getHook('displayFooter'));
        $this->assertSame($hookItem3, $handler->getHook('displaySidebar'));
    }

    public function testGetHooks()
    {
        $hookItem1 = new HookItem('displayHeader');
        $hookItem2 = new HookItem('displayFooter');
        $hookItem3 = new HookItem('displaySidebar');

        $handler = new HookHandlerInstaller($this->module, [
            $hookItem1,
            $hookItem2
        ]);

        $handler->addHook($hookItem3);

        $hooks = $handler->getHooks();

        $this->assertCount(3, $hooks);
        $this->assertSame($hookItem1, $hooks['displayHeader']);
        $this->assertSame($hookItem2, $hooks['displayFooter']);
        $this->assertSame($hookItem3, $hooks['displaySidebar']);
    }

    /**
     * @runInSeparateProcess
     */
    public function testInstallThrowsExceptionWhenRegisteringHookFails()
    {
        $this->expectException(FailedRegisterHookException::class);

        Module::$forceReturnFalseOnRegisterHook = true;

        $handler = new HookHandlerInstaller($this->module, [
            new HookItem('displayHeader')
        ]);

        $handler->install();
    }

    public function testInstallReturnsTrue()
    {
        $handler = new HookHandlerInstaller($this->module, [
            new HookItem('displayHeader'),
            new HookItem('displayFooter')
        ]);

        $this->assertTrue($handler->install());
    }

    public function testUninstallReturnsTrue()
    {
        $handler = new HookHandlerInstaller($this->module, [
            new HookItem('displayHeader'),
            new HookItem('displayFooter')
        ]);

        $this->assertTrue($handler->uninstall());
    }
}
