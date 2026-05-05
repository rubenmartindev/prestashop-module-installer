<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Handler\Hook;

use PHPUnit_Framework_MockObject_MockObject;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\Exception\FailedRegisterHookException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\Exception\HooksIsEmptyException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\Exception\HooksMustBeInstanceOfHookItemException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\HookHandler;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\Item\HookItemInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Handler\AbstractHandlerInstallerTestCase;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Stubs\Classes\Module\Module;

class HookHandlerTest extends AbstractHandlerInstallerTestCase
{
    public function testConstructThrowsExceptionWhenEmptyHooks()
    {
        $this->expectException(HooksIsEmptyException::class);

        new HookHandler($this->module, []);
    }

    public function testConstructThrowsExceptionWhenInvalidHooks()
    {
        $this->expectException(HooksMustBeInstanceOfHookItemException::class);

        new HookHandler($this->module, [
            'invalidHook',
        ]);
    }

    public function testConstruct()
    {
        $hookItem1 = $this->createHookItemMock('displayHeader');
        $hookItem2 = $this->createHookItemMock('displayFooter');

        $handler = new HookHandler($this->module, [
            $hookItem1,
            $hookItem2,
        ]);

        $this->assertCount(2, $handler->getHooks());
        $this->assertSame($hookItem1, $handler->getHook('displayHeader'));
        $this->assertSame($hookItem2, $handler->getHook('displayFooter'));
    }

    public function testAddHook()
    {
        $hookItem1 = $this->createHookItemMock('displayHeader');
        $hookItem2 = $this->createHookItemMock('displayFooter');

        $handler = new HookHandler($this->module, [
            $hookItem1
        ]);

        $handler->addHook($hookItem2);

        $this->assertSame($hookItem1, $handler->getHook('displayHeader'));
        $this->assertSame($hookItem2, $handler->getHook('displayFooter'));
    }

    public function testGetHookReturnsNullWhenHookNotFound()
    {
        $handler = new HookHandler($this->module, [
            $this->createHookItemMock('displayHeader'),
        ]);

        $this->assertNull($handler->getHook('nonExistingHook'));
    }

    public function testGetHookReturnsHookItemWhenFound()
    {
        $hookItem1 = $this->createHookItemMock('displayHeader');
        $hookItem2 = $this->createHookItemMock('displayFooter');

        $handler = new HookHandler($this->module, [
            $hookItem1
        ]);

        $handler->addHook($hookItem2);

        $this->assertSame($hookItem1, $handler->getHook('displayHeader'));
        $this->assertSame($hookItem2, $handler->getHook('displayFooter'));
    }

    public function testRemoveHook()
    {
        $hookItem1 = $this->createHookItemMock('displayHeader');
        $hookItem2 = $this->createHookItemMock('displayFooter');
        $hookItem3 = $this->createHookItemMock('displaySidebar');

        $handler = new HookHandler($this->module, [
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
        $hookItem1 = $this->createHookItemMock('displayHeader');
        $hookItem2 = $this->createHookItemMock('displayFooter');
        $hookItem3 = $this->createHookItemMock('displaySidebar');

        $handler = new HookHandler($this->module, [
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

        $handler = new HookHandler($this->module, [
            $this->createHookItemMock('displayHeader')
        ]);

        $handler->install();
    }

    public function testInstallReturnsTrue()
    {
        $handler = new HookHandler($this->module, [
            $this->createHookItemMock('displayHeader'),
            $this->createHookItemMock('displayFooter')
        ]);

        $this->assertTrue($handler->install());
    }

    public function testUninstallReturnsTrue()
    {
        $handler = new HookHandler($this->module, [
            $this->createHookItemMock('displayHeader'),
            $this->createHookItemMock('displayFooter')
        ]);

        $this->assertTrue($handler->uninstall());
    }

    /**
     * @param string $hookName
     *
     * @return HookItemInterface|PHPUnit_Framework_MockObject_MockObject
     */
    private function createHookItemMock($hookName)
    {
        $hookItem = $this->createMock(HookItemInterface::class);

        $hookItem->method('getName')->willReturn($hookName);

        return $hookItem;
    }
}
