<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Tab\ValueObject;

use PHPUnit\Framework\TestCase;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject\Exception\WordingDomainIsEmptyException;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject\Exception\WordingDomainTypeIsInvalidException;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject\WordingDomain;
use RubenMartinDev\PrestaShopModuleInstaller\ValueObject\ValueObjectInterface;

final class WordingDomainTest extends TestCase
{
    public function testConstructThrowsExceptionWhenTypeIsInvalid()
    {
        $this->expectException(WordingDomainTypeIsInvalidException::class);

        new WordingDomain(1);
    }

    public function testConstructThrowsExceptionWhenStringIsEmpty()
    {
        $this->expectException(WordingDomainIsEmptyException::class);

        new WordingDomain('');
    }

    public function testConstructReturnsValueObject()
    {
        $wordingDomain1 = new WordingDomain(null);
        $wordingDomain2 = new WordingDomain('Modules.MyModule.Navigation');

        $this->assertInstanceOf(ValueObjectInterface::class, $wordingDomain1);
        $this->assertInstanceOf(ValueObjectInterface::class, $wordingDomain2);
    }

    public function testIsEmptyReturnsFalse()
    {
        $wordingDomain = new WordingDomain('Modules.MyModule.Navigation');

        $this->assertFalse($wordingDomain->isEmpty());
    }

    public function testIsEmptyReturnsTrue()
    {
        $wordingDomain = new WordingDomain(null);

        $this->assertTrue($wordingDomain->isEmpty());
    }

    public function testGetValueReturnsString()
    {
        $wordingDomain = new WordingDomain('Modules.MyModule.Navigation');

        $this->assertSame('Modules.MyModule.Navigation', $wordingDomain->getValue());
    }

    public function testGetValueReturnsNull()
    {
        $wordingDomain = new WordingDomain(null);

        $this->assertSame(null, $wordingDomain->getValue());
    }
}
