<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Tab\ValueObject;

use PHPUnit\Framework\TestCase;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject\Exception\IconIsEmptyException;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject\Exception\IconTypeIsInvalidException;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject\Icon;
use RubenMartinDev\PrestaShopModuleInstaller\ValueObject\ValueObjectInterface;

final class IconTest extends TestCase
{
    public function testConstructThrowsExceptionWhenTypeIsInvalid()
    {
        $this->expectException(IconTypeIsInvalidException::class);

        new Icon(1);
    }

    public function testConstructThrowsExceptionWhenStringIsEmpty()
    {
        $this->expectException(IconIsEmptyException::class);

        new Icon('');
    }

    public function testConstructReturnsValueObject()
    {
        $icon1 = new Icon(null);
        $icon2 = new Icon('extension');

        $this->assertInstanceOf(ValueObjectInterface::class, $icon1);
        $this->assertInstanceOf(ValueObjectInterface::class, $icon2);
    }

    public function testIsEmptyReturnsFalse()
    {
        $icon = new Icon('extension');

        $this->assertFalse($icon->isEmpty());
    }

    public function testIsEmptyReturnsTrue()
    {
        $icon = new Icon(null);

        $this->assertTrue($icon->isEmpty());
    }

    public function testGetValueReturnsString()
    {
        $icon = new Icon('extension');

        $this->assertSame('extension', $icon->getValue());
    }

    public function testGetValueReturnsNull()
    {
        $icon = new Icon(null);

        $this->assertSame(null, $icon->getValue());
    }
}
