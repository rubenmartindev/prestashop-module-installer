<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Unit\Handler\Hook;

use PHPUnit_Framework_MockObject_MockObject;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\Exception\FailedRegisterHookException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\Exception\HookHandlerInstallerException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\Exception\HooksIsEmptyException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\Exception\HooksMustBeInstanceOfHookItemException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\HookHandlerInstaller;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\Item\HookItemInterface;
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
        $hookItem1 = $this->createHookItemMock('displayHeader');
        $hookItem2 = $this->createHookItemMock('displayFooter');

        $handler = new HookHandlerInstaller($this->module, [
            $hookItem1,
            $hookItem2,
        ]);

        $this->assertCount(2, $handler->getHooks());
        $this->assertSame($hookItem1, $handler->getHook('displayHeader'));
        $this->assertSame($hookItem2, $handler->getHook('displayFooter'));
    }

    public function testBuildThrowsExceptionWhenKeyNameIsMissing()
    {
        $this->expectException(HookHandlerInstallerException::class);
        $this->expectExceptionMessage('The key name is required');

        HookHandlerInstaller::build(
            $this->module,
            [
                [],
            ]
        );
    }

    public function testBuild()
    {
        $factory = function (array $hook) {
            return $this->createHookItemMock($hook['name']);
        };

        $handler = HookHandlerInstaller::build(
            $this->module,
            [
                [
                    'name' => 'displayHeader',
                ],
                [
                    'name' => 'displayFooter',
                ],
            ],
            $factory
        );

        $hookItem1 = $handler->getHook('displayHeader');
        $hookItem2 = $handler->getHook('displayFooter');

        $this->assertInstanceOf(HookItemInterface::class, $hookItem1);
        $this->assertInstanceOf(HookItemInterface::class, $hookItem2);
    }

    public function testAddHook()
    {
        $hookItem1 = $this->createHookItemMock('displayHeader');
        $hookItem2 = $this->createHookItemMock('displayFooter');

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
            $this->createHookItemMock('displayHeader'),
        ]);

        $this->assertNull($handler->getHook('nonExistingHook'));
    }

    public function testGetHookReturnsHookItemWhenFound()
    {
        $hookItem1 = $this->createHookItemMock('displayHeader');
        $hookItem2 = $this->createHookItemMock('displayFooter');

        $handler = new HookHandlerInstaller($this->module, [
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
        $hookItem1 = $this->createHookItemMock('displayHeader');
        $hookItem2 = $this->createHookItemMock('displayFooter');
        $hookItem3 = $this->createHookItemMock('displaySidebar');

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
            $this->createHookItemMock('displayHeader')
        ]);

        $handler->install();
    }

    public function testInstallReturnsTrue()
    {
        $handler = new HookHandlerInstaller($this->module, [
            $this->createHookItemMock('displayHeader'),
            $this->createHookItemMock('displayFooter')
        ]);

        $this->assertTrue($handler->install());
    }

    public function testUninstallReturnsTrue()
    {
        $handler = new HookHandlerInstaller($this->module, [
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
