<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Tab\Handler;

use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit\Framework\TestCase;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Exception\ItemTypeIsInvalidException;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\Handler\Exception\FailedToCreateTabException;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\Handler\Exception\FailedToDeleteTabException;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\Handler\TabHandler;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\Handler\TabHandlerInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\Item\TabItemInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject\ClassName;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject\Icon;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject\IsActive;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject\IsEnabled;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject\Name;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject\ParentId;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject\Position;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject\RouteName;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject\Wording;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject\WordingDomain;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Resources\ModuleTrait;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Resources\Stubs\Classes\CollectionStub;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Resources\Stubs\Classes\TabStub;
use Tab;

final class TabHandlerTest extends TestCase
{
    use ModuleTrait;

    public function testConstructThrowsExceptionWhenItemsIsInvalid()
    {
        $this->expectException(ItemTypeIsInvalidException::class);

        new TabHandler(
            $this->getModule(),
            ['foobar']
        );
    }

    public function testConstructReturnsHandler()
    {
        $handler = new TabHandler(
            $this->getModule(),
            [$this->createItemMock('AdminMyTab')]
        );

        $this->assertInstanceOf(TabHandlerInterface::class, $handler);
    }

    /**
     * @runInSeparateProcess
     */
    public function testInstallThrowsExceptionWhenCreatingTabFails()
    {
        $this->expectException(FailedToCreateTabException::class);

        TabStub::$forceReturnFalseOnSave = true;

        $handler = new TabHandler(
            $this->getModule(),
            [$this->createItemMock('AdminMyTab'),
        ]);

        $handler->install();
    }

    public function testInstallReturnsTrue()
    {
        $handler = new TabHandler(
            $this->getModule(),
            [$this->createItemMock('AdminMyTab'),
        ]);

        $this->assertTrue($handler->install());
    }

    /**
     * @runInSeparateProcess
     */
    public function testUninstallThrowsExceptionWhenRemovingTabFails()
    {
        $this->expectException(FailedToDeleteTabException::class);

        TabStub::$forceReturnFalseOnDelete = true;

        $tab = new Tab();

        $tab->id        = 1;
        $tab->module    = $this->getModule()->name;

        CollectionStub::$forceElements = [$tab];

        $handler = new TabHandler(
            $this->getModule(),
            [$this->createItemMock('AdminMyTab')]
        );

        $handler->uninstall();
    }

    /**
     * @runInSeparateProcess
     */
    public function testUninstallRetunsTrue()
    {
        $tab = new Tab();

        $tab->id        = 1;
        $tab->module    = $this->getModule()->name;

        CollectionStub::$forceElements = [$tab];

        $handler = new TabHandler(
            $this->getModule(),
            [$this->createItemMock('AdminMyTab')]
        );

        $this->assertTrue($handler->uninstall());
    }

    /**
     * @param string $className
     * @param array $name
     *
     * @return TabItemInterface|PHPUnit_Framework_MockObject_MockObject
     */
    private function createItemMock($className, $name = 'My tab')
    {
        $item = $this->createMock(TabItemInterface::class);

        $item->method('getClassName')->willReturn(new ClassName($className));
        $item->method('getName')->willReturn(new Name($name));
        $item->method('getParentId')->willReturn(new ParentId(1));
        $item->method('getPosition')->willReturn(new Position(5));
        $item->method('isActive')->willReturn(new IsActive(false));
        $item->method('isEnabled')->willReturn(new IsEnabled(false));
        $item->method('getRouteName')->willReturn(new RouteName('adminadmin_my_module_my_tab'));
        $item->method('getIcon')->willReturn(new Icon('extension'));
        $item->method('getWording')->willReturn(new Wording('My tab'));
        $item->method('getWordingDomain')->willReturn(new WordingDomain('Modules.MyModule.Navigation'));

        return $item;
    }
}
