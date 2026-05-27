<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Tab\ValueObject;

use PHPUnit\Framework\TestCase;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject\Position;
use RubenMartinDev\PrestaShopModuleInstaller\ValueObject\ValueObjectInterface;

final class PositionTest extends TestCase
{
    public function testConstructReturnsValueObject()
    {
        $position1 = new Position('-1');
        $position2 = new Position(1);

        $this->assertInstanceOf(ValueObjectInterface::class, $position1);
        $this->assertInstanceOf(ValueObjectInterface::class, $position2);
    }

    public function testGetValueReturnsInteger()
    {
        $position1 = new Position('-1');
        $position2 = new Position(1);

        $this->assertSame(-1, $position1->getValue());
        $this->assertSame(1, $position2->getValue());
    }
}
