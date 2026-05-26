<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Database\ValueObject;

use PHPUnit\Framework\TestCase;
use RubenMartinDev\PrestaShopModuleInstaller\Database\ValueObject\KeepData;
use RubenMartinDev\PrestaShopModuleInstaller\ValueObject\ValueObjectInterface;

final class KeepDataTest extends TestCase
{
    public function testConstructReturnsValueObject()
    {
        $keepData1 = new KeepData(true);
        $keepData2 = new KeepData(false);

        $this->assertInstanceOf(ValueObjectInterface::class, $keepData1);
        $this->assertInstanceOf(ValueObjectInterface::class, $keepData2);
    }

    public function testGetValueReturnsBool()
    {
        $keepData1 = new KeepData(true);
        $keepData2 = new KeepData(false);

        $this->assertTrue($keepData1->getValue());
        $this->assertFalse($keepData2->getValue());
    }
}
