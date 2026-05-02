<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Unit\Handler\Database\Item;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamContainer;
use PHPUnit\Framework\TestCase;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Database\Item\DatabaseItem;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Database\Item\Exception\QuerFileNotExistsException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Database\Item\Exception\QueryFileIsEmptyException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Database\Item\Exception\QueryFileIsNotRedeableException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Database\Item\Exception\QueryFileIsNotStringException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Database\Item\Exception\TableNameIsEmptyException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Database\Item\Exception\TableNameIsNotStringException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Database\Item\Exception\TableNameIsNotValidException;

class DatabaseItemTest extends TestCase
{
    /** @var vfsStreamContainer */
    private $directory;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        if (!\defined('_DB_PREFIX_')) {
            \define('_DB_PREFIX_', 'ps_');
        }

        if (!\defined('_MYSQL_ENGINE_')) {
            \define('_MYSQL_ENGINE_', 'InnoDB');
        }
    }

    public function setUp()
    {
        parent::setUp();

        $this->directory = vfsStream::setup();

        vfsStream::newFile('no_readable.sql', 0000)
            ->at($this->directory)
        ;

        vfsStream::newFile('my_table.sql')
            ->withContent('CREATE TABLE IF NOT EXISTS `{{DB_PREFIX}}my_table` (id INT) ENGINE={{ENGINE_TYPE}};')
            ->at($this->directory)
        ;

        vfsStream::newFile('empty.sql')
            ->withContent('   ')
            ->at($this->directory)
        ;
    }

    public function testConstructThrowsExceptionWhenTableNameIsNotString()
    {
        $this->expectException(TableNameIsNotStringException::class);

        new DatabaseItem(1, 'example.sql');
    }

    public function testConstructThrowsExceptionWhenTableNameIsEmpty()
    {
        $this->expectException(TableNameIsEmptyException::class);

        new DatabaseItem('', 'example.sql');
    }

    public function testConstructThrowsExceptionWhenTableNameIsNotValid()
    {
        $this->expectException(TableNameIsNotValidException::class);

        new DatabaseItem('my table', 'example.sql');
    }

    public function testConstructThrowsExceptionWhenQueryFileIsNotString()
    {
        $this->expectException(QueryFileIsNotStringException::class);

        new DatabaseItem('my_table', 1);
    }

    public function testConstructThrowsExceptionWhenQueryFileIsNotFound()
    {
        $this->expectException(QuerFileNotExistsException::class);

        new DatabaseItem('my_table', 'non_existent_file.sql');
    }

    public function testConstructThrowsExceptionWhenQueryFileIsNotReadable()
    {
        $this->expectException(QueryFileIsNotRedeableException::class);

        new DatabaseItem('my_table', vfsStream::url('root/no_readable.sql'));
    }

    public function testConstruct()
    {
        $queryFile = vfsSTream::url('root/my_table.sql');

        $databaseItem = new DatabaseItem('my_table', $queryFile, true);

        $this->assertSame('my_table', $databaseItem->getTableName());
        $this->assertSame($queryFile, $databaseItem->getQueryFile());
        $this->assertTrue($databaseItem->getKeepData());
    }

    public function testGetTableName()
    {
        $databaseItem = new DatabaseItem('my_table', vfsSTream::url('root/my_table.sql'));

        $this->assertSame('my_table', $databaseItem->getTableName());
    }

    public function testGetQueryThrowsExceptionWhenQueryIsEmpty()
    {
        $this->expectException(QueryFileIsEmptyException::class);

        $databaseItem = new DatabaseItem('my_table', vfsSTream::url('root/empty.sql'));

        $databaseItem->getQuery();
    }

    public function testGetQuery()
    {
        $databaseItem = new DatabaseItem('my_table', vfsSTream::url('root/my_table.sql'));

        $this->assertSame(
            'CREATE TABLE IF NOT EXISTS `ps_my_table` (id INT) ENGINE=InnoDB;',
            $databaseItem->getQuery()
        );
    }

    public function testGetQueryFile()
    {
        $queryFile = vfsSTream::url('root/my_table.sql');

        $databaseItem = new DatabaseItem('my_table', $queryFile);

        $this->assertSame($queryFile, $databaseItem->getQueryFile());
    }

    public function testGetKeepData()
    {
        $databaseItem = new DatabaseItem('my_table', vfsSTream::url('root/my_table.sql'), true);

        $this->assertTrue($databaseItem->getKeepData());
    }
}
