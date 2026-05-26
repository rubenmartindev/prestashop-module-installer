<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Database\ValueObject;

use PHPUnit\Framework\TestCase;
use RubenMartinDev\PrestaShopModuleInstaller\Database\ValueObject\Exception\QueryIsEmptyException;
use RubenMartinDev\PrestaShopModuleInstaller\Database\ValueObject\Exception\QueryTypeIsInvalidException;
use RubenMartinDev\PrestaShopModuleInstaller\Database\ValueObject\Query;
use RubenMartinDev\PrestaShopModuleInstaller\ValueObject\ValueObjectInterface;

final class QueryTest extends TestCase
{
    public function testConstructThrowsExceptionWhenTypeIsNotValid()
    {
        $this->expectException(QueryTypeIsInvalidException::class);

        new Query(1);
    }

    public function testConstructThrowsExceptionWhenStringIsEmpty()
    {
        $this->expectException(QueryIsEmptyException::class);

        new Query('');
    }

    public function testConstructReturnsValueObject()
    {
        $query1 = new Query('SELECT * FROM my_table');
        $query2 = new Query(null);

        $this->assertInstanceOf(ValueObjectInterface::class, $query1);
        $this->assertInstanceOf(ValueObjectInterface::class, $query2);
    }

    public function testIsEmptyReturnsFalseWhenIsNotEmpty()
    {
        $query = new Query('SELECT * FROM my_table');

        $this->assertFalse($query->isEmpty());
    }

    public function testIsEmptyReturnsTrueWhenIsEmpty()
    {
        $query = new Query(null);

        $this->assertTrue($query->isEmpty());
    }

    public function testGetValueReturnsStringOrNull()
    {
        $value1 = 'SELECT * FROM my_table';
        $value2 = null;

        $query1 = new Query($value1);
        $query2 = new Query($value2);

        $this->assertSame($value1, $query1->getValue());
        $this->assertSame($value2, $query2->getValue());
    }
}
