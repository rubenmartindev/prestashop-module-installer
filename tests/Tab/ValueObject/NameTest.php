<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Tab\ValueObject;

use PHPUnit\Framework\TestCase;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject\Exception\DefaultLanguageIdIsMissingInNameException;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject\Exception\KeyMustBeNumericInNameExpection;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject\Exception\NameIsEmptyException;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject\Exception\NameTypeIsInvalidException;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject\Exception\ValueIsEmptyInNameExpection;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject\Exception\ValueMustBeStringInNameExpection;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject\Name;
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

    public function testConstructThrowsExceptionWhenArrayIsEmpty()
    {
        $this->expectException(NameIsEmptyException::class);

        new Name([]);
    }

    public function testConstructThrowsExceptionWhenArrayKeyIsNotNumeric()
    {
        $this->expectException(KeyMustBeNumericInNameExpection::class);

        new Name(['foo' => 'My tab']);
    }

    public function testConstructThrowsExceptionWhenArrayValueIsNotString()
    {
        $this->expectException(ValueMustBeStringInNameExpection::class);

        new Name([1 => 1]);
    }

    public function testConstructThrowsExceptionWhenArrayValueIsEmpty()
    {
        $this->expectException(ValueIsEmptyInNameExpection::class);

        new Name([1 => '']);
    }

    public function testConstructThrowsExceptionWhenArrayWithoutDefaultLanguageIdKey()
    {
        $this->expectException(DefaultLanguageIdIsMissingInNameException::class);

        new Name([2 => 'My tab']);
    }

    public function testConstructRetursValueObjectWhenIsString()
    {
        $name = new Name('My tab');

        $this->assertInstanceOf(ValueObjectInterface::class, $name);
    }

    public function testConstructRetursValueObjectWhenIsArray()
    {
        $name = new Name([1 => 'My tab']);

        $this->assertInstanceOf(ValueObjectInterface::class, $name);
    }

    public function testGetDefaultLanguageValueReturnsString()
    {
        $name1 = new Name('My tab');
        $name2 = new Name([1 => 'My another tab 1', 2 => 'My another tab 2']);

        $this->assertSame('My tab', $name1->getDefaultLanguageValue());
        $this->assertSame('My another tab 1', $name2->getDefaultLanguageValue());
    }

    public function testGetValueReturnsArray()
    {
        $value = [1 => 'My another tab 1', 2 => 'My another tab 2'];

        $name1 = new Name('My tab');
        $name2 = new Name($value);

        $this->assertSame([1 => 'My tab', 2 => 'My tab'], $name1->getValue());
        $this->assertSame($value, $name2->getValue());
    }

    public function testGetValueForLanguageIdReturnsStringWhenIdNotExists()
    {
        $name1 = new Name('My tab');
        $name2 = new Name([1 => 'My another tab 1', 2 => 'My another tab 2']);

        $this->assertSame('My tab', $name1->getValueForLanguageId(999));
        $this->assertSame('My another tab 1', $name2->getValueForLanguageId(999));
    }

    public function testGetValueForLanguageIdReturnsStringWhenIdExists()
    {
        $name1 = new Name('My tab') ;
        $name2 = new Name([1 => 'My another tab 1', 2 => 'My another tab 2']);

        $this->assertSame('My tab', $name1->getValueForLanguageId(2));
        $this->assertSame('My another tab 2', $name2->getValueForLanguageId(2));
    }
}
