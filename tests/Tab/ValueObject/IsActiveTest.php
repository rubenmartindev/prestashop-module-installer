<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Tab\ValueObject;

use PHPUnit\Framework\TestCase;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject\IsActive;
use RubenMartinDev\PrestaShopModuleInstaller\ValueObject\ValueObjectInterface;

final class IsActiveTest extends TestCase
{
    public function testConstructReturnsValueObject()
    {
        $isActive1 = new IsActive(true);
        $isActive2 = new IsActive(false);

        $this->assertInstanceOf(ValueObjectInterface::class, $isActive1);
        $this->assertInstanceOf(ValueObjectInterface::class, $isActive2);
    }

    public function testGetValueReturnsBool()
    {
        $isActive1 = new IsActive(true);
        $isActive2 = new IsActive(false);

        $this->assertTrue($isActive1->getValue());
        $this->assertFalse($isActive2->getValue());
    }
}
