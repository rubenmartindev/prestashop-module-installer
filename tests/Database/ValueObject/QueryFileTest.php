<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Database\ValueObject;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamContainer;
use PHPUnit\Framework\TestCase;
use RubenMartinDev\PrestaShopModuleInstaller\Database\ValueObject\Exception\QueryFileIsEmptyException;
use RubenMartinDev\PrestaShopModuleInstaller\Database\ValueObject\Exception\QueryFileIsNotRedeableException;
use RubenMartinDev\PrestaShopModuleInstaller\Database\ValueObject\Exception\QueryFileNotExistsException;
use RubenMartinDev\PrestaShopModuleInstaller\Database\ValueObject\Exception\QueryFileTypeIsInvalidException;
use RubenMartinDev\PrestaShopModuleInstaller\Database\ValueObject\QueryFile;
use RubenMartinDev\PrestaShopModuleInstaller\ValueObject\ValueObjectInterface;

final class QueryFileTest extends TestCase
{
    /** @var vfsStreamContainer */
    private $directory;

    public function setUp()
    {
        parent::setUp();

        $this->directory = vfsStream::setup();

        vfsStream::newFile('no_readable.sql', 0000)
            ->at($this->directory)
        ;

        vfsStream::newFile('redeable.sql')
            ->at($this->directory)
        ;

        vfsStream::newFile('another_redeable.sql')
            ->at($this->directory)
        ;
    }

    public function testConstructThrowsExceptionWhenTypeIsNotValid()
    {
        $this->expectException(QueryFileTypeIsInvalidException::class);

        new QueryFile(1);
    }

    public function testConstructThrowsExceptionWhenStringIsEmpty()
    {
        $this->expectException(QueryFileIsEmptyException::class);

        new QueryFile('');
    }

    public function testConstructThrowsExceptionWhenFileNotExists()
    {
        $this->expectException(QueryFileNotExistsException::class);

        new QueryFile('non_existent_file.sql');
    }

    public function testConstructThrowsExceptionWhenFileIsNotRedeable()
    {
        $this->expectException(QueryFileIsNotRedeableException::class);

        new QueryFile(vfsStream::url('root/no_readable.sql'));
    }

    public function testConstructReturnsValueObject()
    {
        $queryFile1 = new QueryFile(vfsStream::url('root/redeable.sql'));
        $queryFile2 = new QueryFile(null);

        $this->assertInstanceOf(ValueObjectInterface::class, $queryFile1);
        $this->assertInstanceOf(ValueObjectInterface::class, $queryFile2);
    }

    public function testIsEmptyReturnsFalseWhenIsNotEmpty()
    {
        $queryFile = new QueryFile(vfsStream::url('root/redeable.sql'));

        $this->assertFalse($queryFile->isEmpty());
    }

    public function testIsEmptyReturnsTrueWhenIsEmpty()
    {
        $queryFile = new QueryFile(null);

        $this->assertTrue($queryFile->isEmpty());
    }

    public function testGetValueReturnsStringOrNull()
    {
        $value1 = vfsStream::url('root/redeable.sql');
        $value2 = null;

        $queryFile1 = new QueryFile($value1);
        $queryFile2 = new QueryFile($value2);

        $this->assertSame($value1, $queryFile1->getValue());
        $this->assertSame($value2, $queryFile2->getValue());
    }
}
