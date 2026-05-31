<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Configuration\ValueObject;

use PHPUnit\Framework\TestCase;
use RubenMartinDev\PrestaShopModuleInstaller\Configuration\ValueObject\Exception\NameIsEmptyException;
use RubenMartinDev\PrestaShopModuleInstaller\Configuration\ValueObject\Exception\NameIsNotValidException;
use RubenMartinDev\PrestaShopModuleInstaller\Configuration\ValueObject\Exception\NamePrefixTypeIsInvalidException;
use RubenMartinDev\PrestaShopModuleInstaller\Configuration\ValueObject\Exception\NameTypeIsInvalidException;
use RubenMartinDev\PrestaShopModuleInstaller\Configuration\ValueObject\Name;
use RubenMartinDev\PrestaShopModuleInstaller\ValueObject\ValueObjectInterface;

final class NameTest extends TestCase
{
    public function testConstructThrowsExceptionWhenTypeNameIsNotValid()
    {
        $this->expectException(NameTypeIsInvalidException::class);

        new Name(1);
    }

    public function testConstructThrowsExceptionWhenStringNameIsEmpty()
    {
        $this->expectException(NameIsEmptyException::class);

        new Name('');
    }

    public function testConstructThrowsExceptionWhenStringNameIsInvalid()
    {
        $this->expectException(NameIsNotValidException::class);

        new Name('invalid name');
    }

    public function testConstructThrowsExceptionWhenTypePrefixIsNotInvalid()
    {
        $this->expectException(NamePrefixTypeIsInvalidException::class);

        new Name('my_configuration', 1);
    }

    public function testConstructReturnsValueObject()
    {
        $name1 = new Name('my_configuration');
        $name2 = new Name('my_configuration', 'prefix');

        $this->assertInstanceOf(ValueObjectInterface::class, $name1);
        $this->assertInstanceOf(ValueObjectInterface::class, $name2);
    }

    public function testGetValueReturnsString()
    {
        $value = 'my_configuration';

        $name1 = new Name($value);
        $name2 = new Name($value, 'prefix');

        $this->assertSame('MY_CONFIGURATION', $name1->getValue());
        $this->assertSame('PREFIX_MY_CONFIGURATION', $name2->getValue());
    }

    public function testGetNameValueReturnsString()
    {
        $value = 'my_configuration';

        $name1 = new Name($value);
        $name2 = new Name($value, 'prefix');

        $this->assertSame('MY_CONFIGURATION', $name1->getNameValue());
        $this->assertSame('MY_CONFIGURATION', $name2->getNameValue());
    }

    public function testGetPrefixValueReturnsString()
    {
        $value = 'my_configuration';

        $name1 = new Name($value);
        $name2 = new Name($value, 'prefix');

        $this->assertSame(null, $name1->getPrefixValue());
        $this->assertSame('PREFIX', $name2->getPrefixValue());
    }
}
