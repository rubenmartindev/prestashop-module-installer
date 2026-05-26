<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Database\ValueObject;

use PHPUnit\Framework\TestCase;
use RubenMartinDev\PrestaShopModuleInstaller\Database\ValueObject\Exception\QueryIsEmptyException;
use RubenMartinDev\PrestaShopModuleInstaller\Database\ValueObject\Exception\QueryTypeIsInvalidException;
use RubenMartinDev\PrestaShopModuleInstaller\Database\ValueObject\Query;

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

        $this->assertInstanceOf(Query::class, $query1);
        $this->assertInstanceOf(Query::class, $query2);
    }

    public function testIsEqualsReturnsFalseWhenIsNotSameValues()
    {
        $value1 = 'SELECT * FROM my_table';
        $value2 = 'SELECT * FROM another_table';
        $value3 = null;

        $query1 = new Query($value1);
        $query2 = new Query($value2);
        $query3 = new Query($value3);

        $this->assertFalse($query1->isEquals($value2));
        $this->assertFalse($query1->isEquals($value3));
        $this->assertFalse($query1->isEquals($query2));
        $this->assertFalse($query1->isEquals($query3));
    }

    public function testIsEqualsReturnsTrueWhenIsSameValues()
    {
        $value1 = 'SELECT * FROM my_table';
        $value2 = null;

        $queryWithString1 = new Query($value1);
        $queryWithString2 = new Query($value1);

        $queryWithNull1 = new Query($value2);
        $queryWithNull2 = new Query($value2);

        $this->assertTrue($queryWithString1->isEquals($value1));
        $this->assertTrue($queryWithString1->isEquals($queryWithString2));
        $this->assertTrue($queryWithNull1->isEquals($value2));
        $this->assertTrue($queryWithNull1->isEquals($queryWithNull2));
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
