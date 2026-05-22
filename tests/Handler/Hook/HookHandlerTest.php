<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Handler\Hook;

use PHPUnit_Framework_MockObject_MockObject;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\Exception\FailedRegisterHookException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\Exception\HooksIsEmptyException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\Exception\HooksMustBeInstanceOfHookItemException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\HookHandler;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\HookHandlerInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\Item\HookItemInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Handler\AbstractHandlerInstallerTestCase;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Stubs\Classes\Module\ModuleStub;

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

    public function testConstructReturnsInstanceOfHookHandlerInterface()
    {
        $hookItem1 = $this->createHookItemMock('displayHeader');
        $hookItem2 = $this->createHookItemMock('displayFooter');

        $handler = new HookHandler($this->module, [
            $hookItem1,
            $hookItem2,
        ]);

        $this->assertInstanceOf(HookHandlerInterface::class, $handler);
        $this->assertCount(2, $handler->getHooks());
        $this->assertSame($hookItem1, $handler->getHook('displayHeader'));
        $this->assertSame($hookItem2, $handler->getHook('displayFooter'));
    }

    public function testAddHook()
    {
        $hookItem1 = $this->createHookItemMock('displayHeader');
        $hookItem2 = $this->createHookItemMock('displayFooter');

        $handler = new HookHandler($this->module, [
            $hookItem1,
        ]);

        $result = $handler->addHook($hookItem2);

        $this->assertInstanceOf(HookHandlerInterface::class, $result);
        $this->assertCount(2, $handler->getHooks());
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
            $hookItem1,
        ]);

        $handler->addHook($hookItem2);

        $this->assertSame($hookItem1, $handler->getHook('displayHeader'));
        $this->assertSame($hookItem2, $handler->getHook('displayFooter'));
    }

    public function testRemoveHook()
    {
        $hookItem1 = $this->createHookItemMock('displayHeader');
        $hookItem2 = $this->createHookItemMock('displayFooter');

        $handler = new HookHandler($this->module, [
            $hookItem1,
            $hookItem2,
        ]);

        $result = $handler->removeHook('displayHeader');

        $this->assertInstanceOf(HookHandlerInterface::class, $result);
        $this->assertCount(1, $handler->getHooks());
        $this->assertNull($handler->getHook('displayHeader'));
        $this->assertSame($hookItem2, $handler->getHook('displayFooter'));
    }

    public function testGetHooks()
    {
        $hookItem1 = $this->createHookItemMock('displayHeader');
        $hookItem2 = $this->createHookItemMock('displayFooter');

        $handler = new HookHandler($this->module, [
            $hookItem1,
            $hookItem2,
        ]);

        $hooks = $handler->getHooks();

        $this->assertCount(2, $hooks);
        $this->assertSame($hookItem1, $hooks['displayHeader']);
        $this->assertSame($hookItem2, $hooks['displayFooter']);
    }

    /**
     * @runInSeparateProcess
     */
    public function testInstallThrowsExceptionWhenRegisteringHookFails()
    {
        $this->expectException(FailedRegisterHookException::class);

        ModuleStub::$forceReturnFalseOnRegisterHook = true;

        $handler = new HookHandler($this->module, [
            $this->createHookItemMock('displayHeader'),
        ]);

        $handler->install();
    }

    /**
     * @runInSeparateProcess
     */
    public function testInstallReturnsTrue()
    {
        \define('_PS_VERSION_', 1.0);

        $module = $this->getModule(['registerHook']);

        $module
            ->expects($this->exactly(4))
            ->method('registerHook')
            ->willReturn(true)
        ;

        $handler = new HookHandler($module, [
            $this->createHookItemMock('displayHeader'),
            $this->createHookItemMock('displayFooter', null),
            $this->createHookItemMock('displaySidebar', '>=2.0'),
            $this->createHookItemMock('displayContent', '>=0.0'),
            $this->createHookItemMock('displayModal', '>=0.0', '<1.0'),
        ]);

        $this->assertTrue($handler->install());
    }

    public function testUninstallReturnsTrue()
    {
        $handler = new HookHandler($this->module, [
            $this->createHookItemMock('displayHeader'),
            $this->createHookItemMock('displayFooter'),
        ]);

        $this->assertTrue($handler->uninstall());
    }

    /**
     * @param string $hookName
     * @param string|null $versionMin
     * @param string|null $versionMax
     *
     * @return HookItemInterface|PHPUnit_Framework_MockObject_MockObject
     */
    private function createHookItemMock($hookName, $versionMin = null, $versionMax = null)
    {
        $hookItem = $this->createMock(HookItemInterface::class);

        $hookItem->method('getName')->willReturn($hookName);
        $hookItem->method('getPrestaShopVersion')->willReturn([
            'min' => $versionMin,
            'max' => $versionMax,
        ]);

        return $hookItem;
    }
}
