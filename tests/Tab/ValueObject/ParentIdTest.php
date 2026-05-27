<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Tab\ValueObject;

use PHPUnit\Framework\TestCase;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject\Exception\ParentIdIsEmptyException;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject\Exception\ParentIdTypeIsInvalidException;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject\ParentId;
use RubenMartinDev\PrestaShopModuleInstaller\ValueObject\ValueObjectInterface;

final class ParentIdTest extends TestCase
{
    public function testConstructThowsExceptionWhenTypeIsInvalid()
    {
        $this->expectException(ParentIdTypeIsInvalidException::class);

        new ParentId(true);
    }

    public function testConstructThowsExceptionWhenStringIsEmpty()
    {
        $this->expectException(ParentIdIsEmptyException::class);

        new ParentId('');
    }

    public function testConstructReturnsValueObject()
    {
        $parentId1 = new ParentId(0);
        $parentId2 = new ParentId(-1);
        $parentId3 = new ParentId(1);
        $parentId4 = new ParentId('0');
        $parentId5 = new ParentId('-1');
        $parentId6 = new ParentId('1');
        $parentId7 = new ParentId('AdminParentTab');

        $this->assertInstanceOf(ValueObjectInterface::class, $parentId1);
        $this->assertInstanceOf(ValueObjectInterface::class, $parentId2);
        $this->assertInstanceOf(ValueObjectInterface::class, $parentId3);
        $this->assertInstanceOf(ValueObjectInterface::class, $parentId4);
        $this->assertInstanceOf(ValueObjectInterface::class, $parentId5);
        $this->assertInstanceOf(ValueObjectInterface::class, $parentId6);
        $this->assertInstanceOf(ValueObjectInterface::class, $parentId7);
    }

    public function testGetValueReturnInteger()
    {
        $parentId1 = new ParentId(0);
        $parentId2 = new ParentId(-1);
        $parentId3 = new ParentId(1);
        $parentId4 = new ParentId('0');
        $parentId5 = new ParentId('-1');
        $parentId6 = new ParentId('1');
        $parentId7 = new ParentId('AdminParentTab');

        $this->assertSame(0, $parentId1->getValue());
        $this->assertSame(-1, $parentId2->getValue());
        $this->assertSame(1, $parentId3->getValue());
        $this->assertSame(0, $parentId4->getValue());
        $this->assertSame(-1, $parentId5->getValue());
        $this->assertSame(1, $parentId6->getValue());
        $this->assertSame(1, $parentId7->getValue());
    }
}
