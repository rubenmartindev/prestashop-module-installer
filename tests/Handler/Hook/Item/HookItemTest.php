<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Handler\Hook\Item;

use PHPUnit\Framework\TestCase;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\Item\Exception\NameIsInvalidException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\Item\Exception\PrestaShopVersionIsInvalidException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\Item\Exception\PrestaShopVersionTypeIsInvalidException;
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

    public function testConstructThowsExceptionWhenPrestashopVersionTypeIsInvalid()
    {
        $this->expectException(PrestaShopVersionTypeIsInvalidException::class);

        new HookItem('displayHeader', 1.1);
    }

    public function testConstructThowsExceptionWhenPrestashopVersionTypeIsValidAndValueStringIsInvalid()
    {
        $this->expectException(PrestaShopVersionIsInvalidException::class);

        new HookItem('displayHeader', 'foobar');
    }

    public function testConstructThowsExceptionWhenPrestashopVersionTypeIsValidAndValueArrayMissingKeyMin()
    {
        $this->expectException(PrestaShopVersionIsInvalidException::class);

        new HookItem('displayHeader', []);
    }

    public function testConstructThowsExceptionWhenPrestashopVersionTypeIsValidAndValueArrayKeyMinIsInvalid()
    {
        $this->expectException(PrestaShopVersionIsInvalidException::class);

        new HookItem('displayHeader', ['min' => 1.1]);
    }

    public function testConstructThowsExceptionWhenPrestashopVersionTypeIsValidAndValueArrayKeyMaxIsInvalid()
    {
        $this->expectException(PrestaShopVersionIsInvalidException::class);

        new HookItem('displayHeader', ['min' => '>=1.0', 'max' => 1.1]);
    }

    public function testConstructReturnsInstanceOfHookItemInterfaces()
    {
        $item = new HookItem('displayHeader');

        $this->assertInstanceOf(HookItemInterface::class, $item);
    }

    public function testConstructReturnsInstanceOfHookItemInterfacesWhenPrestaShopVersionIsNull()
    {
        $item = new HookItem('displayHeader', null);

        $this->assertInstanceOf(HookItemInterface::class, $item);
    }

    public function testConstructReturnsInstanceOfHookItemInterfacesWhenPrestaShopVersionIsString()
    {
        $item = new HookItem('displayHeader', '>=1.0');

        $this->assertInstanceOf(HookItemInterface::class, $item);
    }

    public function testConstructReturnsInstanceOfHookItemInterfacesWhenPrestaShopVersionIsArrayWithKeyMin()
    {
        $item = new HookItem('displayHeader', ['min' => '>=1.0']);

        $this->assertInstanceOf(HookItemInterface::class, $item);
    }

    public function testConstructReturnsInstanceOfHookItemInterfacesWhenPrestaShopVersionIsArrayWithKeysMinAndMax()
    {
        $item = new HookItem('displayHeader', ['min' => '>=1.0', 'max' => '<=2.0']);

        $this->assertInstanceOf(HookItemInterface::class, $item);
    }

    public function testGetNameReturnsString()
    {
        $hookItem = new HookItem('displayHeader');

        $this->assertEquals('displayHeader', $hookItem->getName());
    }

    public function testGetPrestaShopVersionReturnsArrayWhenPrestaShopVersionIsMissing()
    {
        $hookItem = new HookItem('displayHeader');

        $this->assertSame(['min' => null, 'max' => null], $hookItem->getPrestaShopVersion());
    }

    public function testGetPrestaShopVersionReturnsArrayWhenPrestaShopVersionIsString()
    {
        $hookItem = new HookItem('dispayHeader', '>=1.0');

        $this->assertSame(['min' => '>=1.0', 'max' => null], $hookItem->getPrestaShopVersion());
    }

    public function testGetPrestaShopVersionReturnsArrayWhenPrestaShopVersionIsArrayWithKeyMin()
    {
        $hookItem = new HookItem('dispayHeader', ['min' => '>=1.0']);

        $this->assertSame(['min' => '>=1.0', 'max' => null], $hookItem->getPrestaShopVersion());
    }

    public function testGetPrestaShopVersionReturnsArrayWhenPrestaShopVersionIsArrayWithKeysMinAndMax()
    {
        $hookItem = new HookItem('dispayHeader', ['min' => '>=1.0', 'max' => '<=2.0']);

        $this->assertSame(['min' => '>=1.0', 'max' => '<=2.0'], $hookItem->getPrestaShopVersion());
    }
}
