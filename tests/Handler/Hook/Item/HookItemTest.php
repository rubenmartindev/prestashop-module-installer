<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Handler\Hook\Item;

use PHPUnit\Framework\TestCase;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\Item\Exception\NameIsInvalidException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\Item\Exception\PrestaShopVersionIsInvalidException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\Item\HookItem;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\Item\HookItemInterface;

class HookItemTest extends TestCase
{
    public function testConstructThrowsExceptionWhenNameIsEmpty()
    {
        $this->expectException(NameIsInvalidException::class);

        new HookItem('');
    }

    public function testConstructThrowsExceptionWhenNameIsInvalid()
    {
        $this->expectException(NameIsInvalidException::class);

        new HookItem('invalid name');
    }

    public function testConstructThowsExceptionWhenPrestashopVersionIsInvalid()
    {
        $this->expectException(PrestaShopVersionIsInvalidException::class);

        new HookItem('displayHeader', 1.1);
    }

    public function testConstructReturnsInstanceOfHookItemInterfaces()
    {
        $item = new HookItem('displayHeader');

        $this->assertInstanceOf(HookItemInterface::class, $item);
    }

    public function testGetNameReturnsString()
    {
        $hookItem = new HookItem('displayHeader');

        $this->assertEquals('displayHeader', $hookItem->getName());
    }

    public function testGetPrestaShopVersionReturnsNull()
    {
        $hookItem = new HookItem('displayHeader');

        $this->assertNull($hookItem->getPrestaShopVersion());
    }

    public function testGetPrestaShopVersionReturnsString()
    {
        $hookItem = new HookItem('dispayHeader', '>=1.0');

        $this->assertSame('>=1.0', $hookItem->getPrestaShopVersion());
    }
}
