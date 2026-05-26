<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Hook\ValueObject;

use PHPUnit\Framework\TestCase;
use RubenMartinDev\PrestaShopModuleInstaller\Hook\ValueObject\Exception\PrestaShopVersionIsInvalidException;
use RubenMartinDev\PrestaShopModuleInstaller\Hook\ValueObject\Exception\PrestaShopVersionTypeIsInvalidException;
use RubenMartinDev\PrestaShopModuleInstaller\Hook\ValueObject\PrestaShopVersion;
use RubenMartinDev\PrestaShopModuleInstaller\ValueObject\ValueObjectInterface;

final class PrestaShopVersionTest extends TestCase
{
    public function testConstructThrowsExceptionWhenTypeIsNotValid()
    {
        $this->expectException(PrestaShopVersionTypeIsInvalidException::class);

        new PrestaShopVersion(1);
    }

    public function testConstructThrowsExceptionWhenStringIsEmpty()
    {
        $this->expectException(PrestaShopVersionIsInvalidException::class);

        new PrestaShopVersion('');
    }

    public function testConstructThrowsExceptionWhenArrayIsEmpty()
    {
        $this->expectException(PrestaShopVersionIsInvalidException::class);

        new PrestaShopVersion([]);
    }

    public function testConstructReturnsValueObject()
    {
        $prestahopVersion1 = new PrestaShopVersion(null);
        $prestahopVersion2 = new PrestaShopVersion('>=1.6.0.0');
        $prestahopVersion3 = new PrestaShopVersion(['min' => '>=1.6.0.0']);
        $prestahopVersion4 = new PrestaShopVersion(['min' => '>=1.6.0.0', 'max' => '<=1.7.0.0']);

        $this->assertInstanceOf(ValueObjectInterface::class, $prestahopVersion1);
        $this->assertInstanceOf(ValueObjectInterface::class, $prestahopVersion2);
        $this->assertInstanceOf(ValueObjectInterface::class, $prestahopVersion3);
        $this->assertInstanceOf(ValueObjectInterface::class, $prestahopVersion4);
    }

    public function testGetValueReturnsArray()
    {
        $prestahopVersion1 = new PrestaShopVersion(null);
        $prestahopVersion2 = new PrestaShopVersion('>=1.6.0.0');
        $prestahopVersion3 = new PrestaShopVersion(['min' => '>=1.6.0.0']);
        $prestahopVersion4 = new PrestaShopVersion(['min' => '>=1.6.0.0', 'max' => '<=1.7.0.0']);

        $this->assertSame(['min' => null, 'max' => null], $prestahopVersion1->getValue());
        $this->assertSame(['min' => '>=1.6.0.0', 'max' => null], $prestahopVersion2->getValue());
        $this->assertSame(['min' => '>=1.6.0.0', 'max' => null], $prestahopVersion3->getValue());
        $this->assertSame(['min' => '>=1.6.0.0', 'max' => '<=1.7.0.0'], $prestahopVersion4->getValue());
    }

    public function testGetMinValueReturnStringOrNull()
    {
        $prestahopVersion1 = new PrestaShopVersion(null);
        $prestahopVersion2 = new PrestaShopVersion('>=1.6.0.0');
        $prestahopVersion3 = new PrestaShopVersion(['min' => '>=1.6.0.0']);
        $prestahopVersion4 = new PrestaShopVersion(['min' => '>=1.6.0.0', 'max' => '<=1.7.0.0']);

        $this->assertSame(null, $prestahopVersion1->getMinValue());
        $this->assertSame('>=1.6.0.0', $prestahopVersion2->getMinValue());
        $this->assertSame('>=1.6.0.0', $prestahopVersion3->getMinValue());
        $this->assertSame('>=1.6.0.0', $prestahopVersion4->getMinValue());
    }

    public function testGetMaxValueReturnStringOrNull()
    {
        $prestahopVersion1 = new PrestaShopVersion(null);
        $prestahopVersion2 = new PrestaShopVersion('>=1.6.0.0');
        $prestahopVersion3 = new PrestaShopVersion(['min' => '>=1.6.0.0']);
        $prestahopVersion4 = new PrestaShopVersion(['min' => '>=1.6.0.0', 'max' => '<=1.7.0.0']);

        $this->assertSame(null, $prestahopVersion1->getMaxValue());
        $this->assertSame(null, $prestahopVersion2->getMaxValue());
        $this->assertSame(null, $prestahopVersion3->getMaxValue());
        $this->assertSame('<=1.7.0.0', $prestahopVersion4->getMaxValue());
    }
}
