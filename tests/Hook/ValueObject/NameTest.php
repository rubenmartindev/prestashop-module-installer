<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Hook\ValueObject;

use PHPUnit\Framework\TestCase;
use RubenMartinDev\PrestaShopModuleInstaller\Hook\ValueObject\Exception\NameIsEmptyException;
use RubenMartinDev\PrestaShopModuleInstaller\Hook\ValueObject\Exception\NameIsNotValidException;
use RubenMartinDev\PrestaShopModuleInstaller\Hook\ValueObject\Exception\NameTypeIsInvalidException;
use RubenMartinDev\PrestaShopModuleInstaller\Hook\ValueObject\Name;
use RubenMartinDev\PrestaShopModuleInstaller\ValueObject\ValueObjectInterface;

final class NameTest extends TestCase
{
    public function testConstructThrowsExceptionWhenTypeIsNotValid()
    {
        $this->expectException(NameTypeIsInvalidException::class);

        new Name(1);
    }

    public function testConstructThrowsExceptionWhenStringIsEmpty()
    {
        $this->expectException(NameIsEmptyException::class);

        new Name('');
    }

    public function testConstructThrowsExceptionWhenStringIsInvalid()
    {
        $this->expectException(NameIsNotValidException::class);

        new Name('invalid name');
    }

    public function testConstructReturnsValueObject()
    {
        $name = new Name('displayHeader');

        $this->assertInstanceOf(ValueObjectInterface::class, $name);
    }

    public function testGetValueReturnsString()
    {
        $value = 'displayHeader';

        $name = new Name($value);

        $this->assertSame($value, $name->getValue());
    }
}
