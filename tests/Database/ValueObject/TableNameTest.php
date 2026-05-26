<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Database\ValueObject;

use PHPUnit\Framework\TestCase;
use RubenMartinDev\PrestaShopModuleInstaller\Database\ValueObject\Exception\TableNameIsEmptyException;
use RubenMartinDev\PrestaShopModuleInstaller\Database\ValueObject\Exception\TableNameIsNotValidException;
use RubenMartinDev\PrestaShopModuleInstaller\Database\ValueObject\Exception\TableNameTypeIsInvalidException;
use RubenMartinDev\PrestaShopModuleInstaller\Database\ValueObject\TableName;

final class TableNameTest extends TestCase
{
    public function testConstructThrowsExceptionWhenTypeIsNotValid()
    {
        $this->expectException(TableNameTypeIsInvalidException::class);

        new TableName(1);
    }

    public function testConstructThrowsExceptionWhenStringIsEmpty()
    {
        $this->expectException(TableNameIsEmptyException::class);

        new TableName('');
    }

    public function testConstructThrowsExceptionWhenStringIsInvalid()
    {
        $this->expectException(TableNameIsNotValidException::class);

        new TableName('my table');
    }

    public function testConstructReturnsValueObject()
    {
        $tableName = new TableName('my_table');

        $this->assertInstanceOf(TableName::class, $tableName);
    }

    public function testIsEqualsReturnsFalseWhenIsNotSameValues()
    {
        $value1 = 'my_table_1';
        $value2 = 'my_table_2';

        $tableName1 = new TableName($value1);
        $tableName2 = new TableName($value2);

        $this->assertFalse($tableName1->isEquals($value2));
        $this->assertFalse($tableName1->isEquals($tableName2));
    }

    public function testIsEqualsReturnsTrueWhenIsSameValues()
    {
        $value = 'my_table';

        $tableName1 = new TableName($value);
        $tableName2 = new TableName($value);

        $this->assertTrue($tableName1->isEquals($value));
        $this->assertTrue($tableName1->isEquals($tableName2));
    }

    public function testGetValueReturnsString()
    {
        $value = 'my_table';

        $tableName = new TableName($value);

        $this->assertSame($value, $tableName->getValue());
    }
}
