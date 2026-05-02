<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Unit\Handler\Tab;

use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Exception\FailedToCreateTabException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Exception\FailedToDeleteTabException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Exception\TabsIsEmptyException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Exception\TabsMustBeInstanceOfTabItemException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Item\TabItem;
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
        $tabItem1 = new TabItem('AdminMyModule1', 'My tab 1');
        $tabItem2 = new TabItem('AdminMyModule2', 'My tab 2');

        $handler = new TabHandlerInstaller($this->module, [
            $tabItem1,
            $tabItem2
        ]);

        $this->assertSame($tabItem1, $handler->getTab('AdminMyModule1'));
        $this->assertSame($tabItem2, $handler->getTab('AdminMyModule2'));
    }

    public function testAddTab()
    {
        $tabItem1 = new TabItem('AdminMyModule1', 'My tab 1');
        $tabItem2 = new TabItem('AdminMyModule2', 'My tab 2');

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
            new TabItem('AdminMyModule', 'My tab'),
        ]);

        $this->assertNull($handler->getTab('NonExistingTab'));
    }

    public function testGetTabReturnsTabItemWhenFound()
    {
        $tabItem = new TabItem('AdminMyModule', 'My tab');

        $handler = new TabHandlerInstaller($this->module, [
            $tabItem,
        ]);

        $this->assertSame($tabItem, $handler->getTab('AdminMyModule'));
    }

    public function testRemoveTab()
    {
        $tabItem1 = new TabItem('AdminMyModule1', 'My tab 1');
        $tabItem2 = new TabItem('AdminMyModule2', 'My tab 2');
        $tabItem3 = new TabItem('AdminMyModule3', 'My tab 3');

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
        $tabItem1 = new TabItem('AdminMyModule1', 'My tab 1');
        $tabItem2 = new TabItem('AdminMyModule2', 'My tab 2');
        $tabItem3 = new TabItem('AdminMyModule3', 'My tab 3');

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
            new TabItem('AdminMyModule', 'My tab'),
        ]);

        $handler->install();
    }

    public function testUninstallReturnsTrue()
    {
        $handler = new TabHandlerInstaller($this->module, [
            new TabItem('AdminMyModule', 'My tab'),
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
            new TabItem('AdminMyModule', 'My tab'),
        ]);

        $handler->uninstall();
    }

    public function testUninstallRetunsTrue()
    {
        $handler = new TabHandlerInstaller($this->module, [
            new TabItem('AdminMyModule', 'My tab'),
        ]);

        $this->assertTrue($handler->uninstall());
    }
}
