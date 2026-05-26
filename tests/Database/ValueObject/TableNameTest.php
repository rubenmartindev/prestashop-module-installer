<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Database\ValueObject;

use PHPUnit\Framework\TestCase;
use RubenMartinDev\PrestaShopModuleInstaller\Database\ValueObject\Exception\TableNameIsEmptyException;
use RubenMartinDev\PrestaShopModuleInstaller\Database\ValueObject\Exception\TableNameIsNotValidException;
use RubenMartinDev\PrestaShopModuleInstaller\Database\ValueObject\Exception\TableNameTypeIsInvalidException;
use RubenMartinDev\PrestaShopModuleInstaller\Database\ValueObject\TableName;
use RubenMartinDev\PrestaShopModuleInstaller\ValueObject\ValueObjectInterface;

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

        $this->assertInstanceOf(ValueObjectInterface::class, $tableName);
    }

    public function testGetValueReturnsString()
    {
        $value = 'my_table';

        $tableName = new TableName($value);

        $this->assertSame($value, $tableName->getValue());
    }
}
