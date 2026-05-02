<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Unit\Handler\Tab;

use PHPUnit_Framework_MockObject_MockObject;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Exception\FailedToCreateTabException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Exception\FailedToDeleteTabException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Exception\TabHandlerInstallerException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Exception\TabsIsEmptyException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Exception\TabsMustBeInstanceOfTabItemException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Item\TabItemInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\TabHandlerInstaller;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Stubs\Classes\Tab;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Unit\Handler\AbstractHandlerInstallerTestCase;

class TabHandlerInstallerTest extends AbstractHandlerInstallerTestCase
{
    public function testConstructThrowsExceptionWhenTabsIsEmpty()
    {
        $this->expectException(TabsIsEmptyException::class);

        new TabHandlerInstaller($this->module, []);
    }

    public function testConstructThrowsExceptionWhenTabsIsNotInstanceOfTabItemInterface()
    {
        $this->expectException(TabsMustBeInstanceOfTabItemException::class);

        new TabHandlerInstaller($this->module, [
            'invalidTab',
        ]);
    }

    public function testConstruct()
    {
        $tabItem1 = $this->createTabItemMock('AdminMyModule1', 'My tab 1');
        $tabItem2 = $this->createTabItemMock('AdminMyModule2', 'My tab 2');

        $handler = new TabHandlerInstaller($this->module, [
            $tabItem1,
            $tabItem2
        ]);

        $this->assertSame($tabItem1, $handler->getTab('AdminMyModule1'));
        $this->assertSame($tabItem2, $handler->getTab('AdminMyModule2'));
    }

    public function testBuildThrowsExceptionWhenKeyClassNameIsMissing()
    {
        $this->expectException(TabHandlerInstallerException::class);
        $this->expectExceptionMessage('The key className is required');

        TabHandlerInstaller::build(
            $this->module,
            [
                [
                    'name' => 'My tab',
                ],
            ]
        );
    }

    public function testBuildThrowsExceptionWhenKeyNameIsMissing()
    {
        $this->expectException(TabHandlerInstallerException::class);
        $this->expectExceptionMessage('The key name is required');

        TabHandlerInstaller::build(
            $this->module,
            [
                [
                    'className' => 'AdminMyModule',
                ],
            ]
        );
    }

    public function testBuild()
    {
        $factory = function (array $tab) {
            return $this->createTabItemMock($tab['className'], $tab['name']);
        };

        $handler = TabHandlerInstaller::build(
            $this->module,
            [
                [
                    'className' => 'AdminMyModule1',
                    'name'      => 'My tab 1',
                ],
                [
                    'className' => 'AdminMyModule2',
                    'name'      => 'My tab 2',
                ],
            ],
            $factory
        );

        $tabItem1 = $handler->getTab('AdminMyModule1');
        $tabItem2 = $handler->getTab('AdminMyModule2');

        $this->assertInstanceOf(TabItemInterface::class, $tabItem1);
        $this->assertInstanceOf(TabItemInterface::class, $tabItem2);
    }

    public function testAddTab()
    {
        $tabItem1 = $this->createTabItemMock('AdminMyModule1', 'My tab 1');
        $tabItem2 = $this->createTabItemMock('AdminMyModule2', 'My tab 2');

        $handler = new TabHandlerInstaller($this->module, [
            $tabItem1
        ]);

        $handler->addTab($tabItem2);

        $this->assertSame($tabItem1, $handler->getTab('AdminMyModule1'));
        $this->assertSame($tabItem2, $handler->getTab('AdminMyModule2'));
    }

    public function testGetTabReturnsNullWhenTabNotFound()
    {
        $handler = new TabHandlerInstaller($this->module, [
            $this->createTabItemMock('AdminMyModule', 'My tab'),
        ]);

        $this->assertNull($handler->getTab('NonExistingTab'));
    }

    public function testGetTabReturnsTabItemWhenFound()
    {
        $tabItem = $this->createTabItemMock('AdminMyModule', 'My tab');

        $handler = new TabHandlerInstaller($this->module, [
            $tabItem,
        ]);

        $this->assertSame($tabItem, $handler->getTab('AdminMyModule'));
    }

    public function testRemoveTab()
    {
        $tabItem1 = $this->createTabItemMock('AdminMyModule1', 'My tab 1');
        $tabItem2 = $this->createTabItemMock('AdminMyModule2', 'My tab 2');
        $tabItem3 = $this->createTabItemMock('AdminMyModule3', 'My tab 3');

        $handler = new TabHandlerInstaller($this->module, [
            $tabItem1,
            $tabItem2,
        ]);

        $handler->addTab($tabItem3);

        $handler->removeTab('AdminMyModule2');
        $handler->removeTab('AdminMyModule3');

        $this->assertNull($handler->getTab('AdminMyModule2'));
        $this->assertNull($handler->getTab('AdminMyModule3'));
        $this->assertSame($tabItem1, $handler->getTab('AdminMyModule1'));
    }

    public function testGetTabs()
    {
        $tabItem1 = $this->createTabItemMock('AdminMyModule1', 'My tab 1');
        $tabItem2 = $this->createTabItemMock('AdminMyModule2', 'My tab 2');
        $tabItem3 = $this->createTabItemMock('AdminMyModule3', 'My tab 3');

        $handler = new TabHandlerInstaller($this->module, [
            $tabItem1,
            $tabItem2,
        ]);

        $handler->addTab($tabItem3);

        $tabs = $handler->getTabs();

        $this->assertCount(3, $tabs);
        $this->assertSame($tabItem1, $tabs['AdminMyModule1']);
        $this->assertSame($tabItem2, $tabs['AdminMyModule2']);
        $this->assertSame($tabItem3, $tabs['AdminMyModule3']);
    }

    /**
     * @runInSeparateProcess
     */
    public function testInstallThrowsExceptionWhenCreatingTabFails()
    {
        $this->expectException(FailedToCreateTabException::class);

        Tab::$forceReturnFalseOnAdd = true;

        $handler = new TabHandlerInstaller($this->module, [
            $this->createTabItemMock('AdminMyModule', 'My tab'),
        ]);

        $handler->install();
    }

    public function testUninstallReturnsTrue()
    {
        $handler = new TabHandlerInstaller($this->module, [
            $this->createTabItemMock('AdminMyModule', 'My tab'),
        ]);

        $this->assertTrue($handler->uninstall());
    }

    /**
     * @runInSeparateProcess
     */
    public function testUninstallThrowsExceptionWhenRemovingTabFails()
    {
        $this->expectException(FailedToDeleteTabException::class);

        Tab::$forceReturnFalseOnDelete = true;

        $handler = new TabHandlerInstaller($this->module, [
            $this->createTabItemMock('AdminMyModule', 'My tab'),
        ]);

        $handler->uninstall();
    }

    public function testUninstallRetunsTrue()
    {
        $handler = new TabHandlerInstaller($this->module, [
            $this->createTabItemMock('AdminMyModule', 'My tab'),
        ]);

        $this->assertTrue($handler->uninstall());
    }

    /**
     * @param string $className
     * @param string $name
     *
     * @return TabItemInterface|PHPUnit_Framework_MockObject_MockObject
     */
    public function createTabItemMock($className, $name)
    {
        $tabItem = $this->createMock(TabItemInterface::class);

        $tabItem->method('getClassName')->willReturn($className);
        $tabItem->method('getName')->willReturn([
            1 => $name,
            2 => $name,
        ]);

        return $tabItem;
    }
}
