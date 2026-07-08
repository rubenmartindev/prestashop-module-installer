<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Tab\Item;

use PHPUnit\Framework\TestCase;
use RubenMartinDev\PrestaShopModuleInstaller\Item\ItemInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\Item\TabItem;
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

final class TabItemTest extends TestCase
{
    /** @var TabItemInterface */
    private $item;

    /** @var ClassName */
    private $className;

    /** @var Name */
    private $name;

    /** @var ParentId */
    private $parentId;

    /** @var Position */
    private $position;

    /** @var IsActive */
    private $isActive;

    /** @var IsEnabled */
    private $isEnabled;

    /** @var RouteName */
    private $routeName;

    /** @var Icon */
    private $icon;

    /** @var Wording */
    private $wording;

    /** @var WordingDomain */
    private $wordingDomain;

    protected function setUp()
    {
        parent::setUp();

        $this->className        = new ClassName('AdminMyModule');
        $this->name             = new Name('My tab');
        $this->parentId         = new ParentId(1);
        $this->position         = new Position(5);
        $this->isActive         = new IsActive(false);
        $this->isEnabled        = new IsEnabled(false);
        $this->routeName        = new RouteName('admin_my_module_my_tab');
        $this->icon             = new Icon('extension');
        $this->wording          = new Wording('My tag');
        $this->wordingDomain    = new WordingDomain('Modules.MyModule.Navigation');

        $this->item = new TabItem(
            $this->className,
            $this->name,
            $this->parentId,
            $this->position,
            $this->isActive,
            $this->isEnabled,
            $this->routeName,
            $this->icon,
            $this->wording,
            $this->wordingDomain
        );
    }

    public function testConstructReturnsTabItem()
    {
        $this->assertInstanceOf(TabItemInterface::class, $this->item);
    }

    public function testConstructForceWordingWhenIsEmpty()
    {
        $item = new TabItem(
            $this->className,
            $this->name,
            $this->parentId,
            $this->position,
            $this->isActive,
            $this->isEnabled,
            $this->routeName,
            $this->icon,
            new Wording(null),
            $this->wordingDomain
        );

        $this->assertSame('My tab', $item->getWording()->getValue());
    }

    public function testConstructForceWordingDomainWhenIsEmpty()
    {
        $item = new TabItem(
            $this->className,
            $this->name,
            $this->parentId,
            $this->position,
            $this->isActive,
            $this->isEnabled,
            $this->routeName,
            $this->icon,
            $this->wording,
            new WordingDomain(null)
        );

        $this->assertSame('Admin.Navigation.Menu', $item->getWordingDomain()->getValue());
    }

    public function testCreateFromReturnTabItemWithRequireParameters()
    {
        $item = TabItem::createFrom(
            'AdminMyTab',
            'My tab'
        );

        $this->assertInstanceOf(TabItemInterface::class, $item);
    }

    public function testCreateFromReturnTabItemWithOptionalParameters()
    {
        $item = TabItem::createFrom(
            'AdminMyTab2',
            [1 => 'My tab2 1', 2 => 'My tab2 2'],
            1,
            5,
            false,
            false,
            'admin_my_module_my_tab',
            'extension',
            'My tag',
            'Modules.MyModule.Navigation'
        );

        $this->assertInstanceOf(TabItemInterface::class, $item);
    }

    public function testGetTypeReturnsString()
    {
        $this->assertEquals('tab', $this->item->getType());
    }

    public function testGetClassNameReturnsValueObject()
    {
        $this->assertSame($this->className, $this->item->getClassName());
    }

    public function testGetNameReturnsValueObject()
    {
        $this->assertSame($this->name, $this->item->getName());
    }

    public function testGetParentIdReturnsValueObject()
    {
        $this->assertSame($this->parentId, $this->item->getParentId());
    }

    public function testGetPositionReturnsValueObject()
    {
        $this->assertSame($this->position, $this->item->getPosition());
    }

    public function testGetRouteNameReturnsValueObject()
    {
        $this->assertSame($this->routeName, $this->item->getRouteName());
    }

    public function testGetIconReturnsValueObject()
    {
        $this->assertSame($this->icon, $this->item->getIcon());
    }

    public function testGetWordingReturnsValueObject()
    {
        $this->assertSame($this->wording, $this->item->getWording());
    }

    public function testGetWordingDomainReturnsValueObject()
    {
        $this->assertSame($this->wordingDomain, $this->item->getWordingDomain());
    }
}
