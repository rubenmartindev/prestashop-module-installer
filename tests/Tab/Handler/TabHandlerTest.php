<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Tab\Handler;

use PHPUnit\Framework\TestCase;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Exception\ItemTypeIsInvalidException;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\Handler\Exception\FailedToCreateTabException;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\Handler\Exception\FailedToDeleteTabException;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\Handler\Exception\ParentTabNotFoundException;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\Handler\TabHandler;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\Handler\TabHandlerInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\Item\TabItem;
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
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Resources\Stubs\Classes\Db\DbStub;
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

    public function testCreateFromReturnHandlerWithRequireParameters()
    {
        $handler = TabHandler::createFrom(
            $this->getModule(),
            [
                [
                    'class_name'    => 'AdminMyTab1',
                    'name'          => 'My tab 1',
                ],
            ]
        );

        $this->assertInstanceOf(TabHandlerInterface::class, $handler);
    }

    public function testCreateFromReturnHandlerWithOptionalParameters()
    {
        $handler = TabHandler::createFrom(
            $this->getModule(),
            [
                [
                    'class_name'        => 'AdminMyTab2',
                    'name'              => [1 => 'My tab2 1', 2 => 'My tab2 2'],
                    'parent_id'         => 1,
                    'position'          => 5,
                    'is_active'         => false,
                    'is_enabled'        => false,
                    'route_name'        => 'admin_my_module_my_tab',
                    'icon'              => 'extension',
                    'wording'           => 'My tag',
                    'wording_domain'    => 'Modules.MyModule.Navigation',
                ],
            ]
        );

        $this->assertInstanceOf(TabHandlerInterface::class, $handler);
    }

    public function testGetItemsReturnsItems()
    {
        $item = $this->createItemMock('AdminMyTab');

        $handler = new TabHandler(
            $this->getModule(),
            [$item]
        );

        $this->assertSame([$item], $handler->getItems());
    }

    public function testAddItemAddsItem()
    {
        $item1 = $this->createItemMock('AdminMyTab');
        $item2 = $this->createItemMock('AdminMyOtherTab');

        $handler = new TabHandler(
            $this->getModule(),
            [$item1]
        );

        $result = $handler->addItem($item2, 5);

        $this->assertSame([0 => $item1, 5 => $item2], $handler->getItems());
        $this->assertSame($result, $handler);
    }

    public function testRemoveItemRemovesItem()
    {
        $item1 = $this->createItemMock('AdminMyTab');
        $item2 = $this->createItemMock('AdminMyOtherTab');

        $handler = new TabHandler(
            $this->getModule(),
            [$item1, $item2]
        );

        $result = $handler->removeItem(0);

        $this->assertSame([1 => $item2], $handler->getItems());
        $this->assertSame($result, $handler);
    }

    public function parentTabNotFoundDataProvider()
    {
        return [
            ['AdminMyTab', 'My tab', 999],
            ['AdminMyTab', 'My tab', 'AdminTabNotExists'],
        ];
    }

    /**
     * @runInSeparateProcess
     *
     * @dataProvider parentTabNotFoundDataProvider
     *
     * @param string $className
     * @param array|string $name
     * @param int|string $parentId
     */
    public function testInstallThrowsExceptionWhenParentTabIsNotFound(
        $className,
        $name,
        $parentId
    ) {
        $this->expectException(ParentTabNotFoundException::class);

        DbStub::$value = false;

        $handler = new TabHandler(
            $this->getModule(),
            [$this->createItemMock($className, $name, $parentId)]
        );

        $handler->install();
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
            [$this->createItemMock('AdminMyTab')]
        );

        $handler->install();
    }

    public function testInstallReturnsTrue()
    {
        $handler = new TabHandler(
            $this->getModule(),
            [$this->createItemMock('AdminMyTab')]
        );

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
     * @param array|string $name
     * @param int|string $parentId
     *
     * @return TabItem
     */
    private function createItemMock($className, $name = 'My tab', $parentId = 1)
    {
        return new TabItem(
            new ClassName($className),
            new Name($name),
            new ParentId($parentId),
            new Position(5),
            new IsActive(false),
            new IsEnabled(false),
            new RouteName('adminadmin_my_module_my_tab'),
            new Icon('extension'),
            new Wording('My tab'),
            new WordingDomain('Modules.MyModule.Navigation')
        );
    }
}
