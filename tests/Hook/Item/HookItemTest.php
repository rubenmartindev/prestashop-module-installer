<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Hook\Item;

use PHPUnit\Framework\TestCase;
use RubenMartinDev\PrestaShopModuleInstaller\Hook\Item\HookItem;
use RubenMartinDev\PrestaShopModuleInstaller\Hook\Item\HookItemInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Hook\ValueObject\Name;
use RubenMartinDev\PrestaShopModuleInstaller\Hook\ValueObject\PrestaShopVersion;

final class HookItemTest extends TestCase
{
    /** @var Name */
    private $name;

    /** @var PrestaShopVersion */
    private $prestashopVersion;

    protected function setUp()
    {
        parent::setUp();

        $this->name                 = new Name('displayHeader');
        $this->prestashopVersion    = new PrestaShopVersion(['min' => '>=1.6.0.0', 'max' => '<=1.7.0.0']);
    }

    public function testConstructReturnsHookItem()
    {
        $item = new HookItem(
            $this->name,
            $this->prestashopVersion
        );

        $this->assertInstanceOf(HookItemInterface::class, $item);
    }

    public function testCreateFromReturnHookItemWithRequireParameters()
    {
        $item = HookItem::createFrom('displayHeader');

        $this->assertInstanceOf(HookItemInterface::class, $item);
    }

    public function testCreateFromReturnHookItemWithOptionalParameters()
    {
        $item = HookItem::createFrom(
            'displayHeader',
            '>=1.6.0.0'
        );

        $this->assertInstanceOf(HookItemInterface::class, $item);
    }

    public function testGetNameReturnsValueObject()
    {
        $item = new HookItem(
            $this->name,
            $this->prestashopVersion
        );

        $this->assertEquals($this->name, $item->getName());
    }

    public function testGetPrestaShopVersionReturnsValueObject()
    {
        $item = new HookItem(
            $this->name,
            $this->prestashopVersion
        );

        $this->assertEquals($this->prestashopVersion, $item->getPrestaShopVersion());
    }
}
