<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Tab\ValueObject;

use PHPUnit\Framework\TestCase;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject\Exception\WordingIsEmptyException;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject\Exception\WordingTypeIsInvalidException;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject\Wording;
use RubenMartinDev\PrestaShopModuleInstaller\ValueObject\ValueObjectInterface;

final class WordingTest extends TestCase
{
    public function testConstructThrowsExceptionWhenTypeIsInvalid()
    {
        $this->expectException(WordingTypeIsInvalidException::class);

        new Wording(1);
    }

    public function testConstructThrowsExceptionWhenStringIsEmpty()
    {
        $this->expectException(WordingIsEmptyException::class);

        new Wording('');
    }

    public function testConstructReturnsValueObject()
    {
        $wording1 = new Wording(null);
        $wording2 = new Wording('My tab');

        $this->assertInstanceOf(ValueObjectInterface::class, $wording1);
        $this->assertInstanceOf(ValueObjectInterface::class, $wording2);
    }

    public function testIsEmptyReturnsFalse()
    {
        $wording = new Wording('My tab');

        $this->assertFalse($wording->isEmpty());
    }

    public function testIsEmptyReturnsTrue()
    {
        $wording = new Wording(null);

        $this->assertTrue($wording->isEmpty());
    }

    public function testGetValueReturnsString()
    {
        $wording = new Wording('My tab');

        $this->assertSame('My tab', $wording->getValue());
    }

    public function testGetValueReturnsNull()
    {
        $wording = new Wording(null);

        $this->assertSame(null, $wording->getValue());
    }
}
