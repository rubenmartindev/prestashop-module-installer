<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Database\ValueObject;

use PHPUnit\Framework\TestCase;
use RubenMartinDev\PrestaShopModuleInstaller\Database\ValueObject\KeepData;

final class KeepDataTest extends TestCase
{
    public function testConstructReturnsValueObject()
    {
        $keepData1 = new KeepData(true);
        $keepData2 = new KeepData(false);

        $this->assertInstanceOf(KeepData::class, $keepData1);
        $this->assertInstanceOf(KeepData::class, $keepData2);
    }

    public function testIsEqualsReturnsFalseWhenIsNotSameValues()
    {
        $keepData1 = new KeepData(true);
        $keepData2 = new KeepData(false);

        $this->assertFalse($keepData1->isEquals(false));
        $this->assertFalse($keepData1->isEquals($keepData2));
    }

    public function testIsEqualsReturnsTrueWhenIsSameValues()
    {
        $keepData1 = new KeepData(true);
        $keepData2 = new KeepData(true);

        $this->assertTrue($keepData1->isEquals(true));
        $this->assertTrue($keepData1->isEquals($keepData2));
    }

    public function testGetValueReturnsBool()
    {
        $keepData1 = new KeepData(true);
        $keepData2 = new KeepData(false);

        $this->assertTrue($keepData1->getValue());
        $this->assertFalse($keepData2->getValue());
    }
}
