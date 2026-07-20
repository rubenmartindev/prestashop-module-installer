<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Database;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamContainer;
use PHPUnit\Framework\TestCase;
use RubenMartinDev\PrestaShopModuleInstaller\Database\Item\DatabaseItem;
use RubenMartinDev\PrestaShopModuleInstaller\Database\Item\DatabaseItemInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Database\Item\Exception\SQLIsEmptyException;
use RubenMartinDev\PrestaShopModuleInstaller\Database\ValueObject\KeepData;
use RubenMartinDev\PrestaShopModuleInstaller\Database\ValueObject\Query;
use RubenMartinDev\PrestaShopModuleInstaller\Database\ValueObject\QueryFile;
use RubenMartinDev\PrestaShopModuleInstaller\Database\ValueObject\TableName;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Resources\ModuleTrait;

final class DatabaseItemTest extends TestCase
{
    use ModuleTrait;

    /** @var vfsStreamContainer */
    private $directory;

    /** @var TableName */
    private $tableName;

    /** @var Query */
    private $query;

    /** @var QueryFile */
    private $queryFile;

    /** @var KeepData */
    private $keepData;

    protected function setUp()
    {
        parent::setUp();

        $this->directory = vfsStream::setup();

        vfsStream::newFile('my_table.sql')
            ->withContent('CREATE TABLE {{DB_PREFIX}}my_table_1 (id INT) engine={{ENGINE_TYPE}}')
            ->at($this->directory)
        ;

        $this->tableName    = new TableName('my_table');
        $this->query        = new Query('CREATE TABLE {{DB_PREFIX}}my_table_2 (id INT) engine={{ENGINE_TYPE}}');
        $this->queryFile    = new QueryFile(vfsStream::url('root/my_table.sql'));
        $this->keepData     = new KeepData(true);
    }

    public function testConstructReturnsDatabaseItem()
    {
        $item = new DatabaseItem(
            $this->tableName,
            $this->query,
            $this->queryFile,
            $this->keepData
        );

        $this->assertInstanceOf(DatabaseItemInterface::class, $item);
    }

    public function testCreateFromReturnDatabaseItemWithRequireParameters()
    {
        $item = DatabaseItem::createFrom($this->getModule(), ['table_name' => 'my_table']);

        $this->assertInstanceOf(DatabaseItemInterface::class, $item);
    }

    public function testCreateFromReturnDatabaseItemWithOptionalParameters()
    {
        $item = DatabaseItem::createFrom(
            $this->getModule(),
            [
                'table_name'    => 'my_table',
                'query'         => 'CREATE TABLE {{DB_PREFIX}}my_table_2 (id INT) engine={{ENGINE_TYPE}}',
                'query_file'    => vfsStream::url('root/my_table.sql'),
                'keep_data'     => true,
            ]
        );

        $this->assertInstanceOf(DatabaseItemInterface::class, $item);
    }

    public function testGetTypeReturnsString()
    {
        $item = new DatabaseItem(
            $this->tableName,
            $this->query,
            $this->queryFile,
            $this->keepData
        );

        $this->assertEquals('database', $item->getType());
    }

    public function testGetTableNameReturnsValueObject()
    {
        $item = new DatabaseItem(
            $this->tableName,
            $this->query,
            $this->queryFile,
            $this->keepData
        );

        $this->assertEquals($this->tableName, $item->getTableName());
    }

    public function testGetQueryReturnsValueObject()
    {
        $item = new DatabaseItem(
            $this->tableName,
            $this->query,
            $this->queryFile,
            $this->keepData
        );

        $this->assertEquals($this->query, $item->getQuery());
    }

    public function testGetQueryFileReturnsValueObject()
    {
        $item = new DatabaseItem(
            $this->tableName,
            $this->query,
            $this->queryFile,
            $this->keepData
        );

        $this->assertEquals($this->queryFile, $item->getQueryFile());
    }

    public function testGetKeepDataReturnsValueObject()
    {
        $item = new DatabaseItem(
            $this->tableName,
            $this->query,
            $this->queryFile,
            $this->keepData
        );

        $this->assertEquals($this->keepData, $item->getKeepData());
    }

    public function testGetSQLThrowsExceptionWhenSQLIsEmpty()
    {
        $item = new DatabaseItem(
            $this->tableName,
            new Query(null),
            new QueryFile(null),
            $this->keepData
        );

        $this->expectException(SQLIsEmptyException::class);

        $item->getSQL();
    }

    public function testGetSQLReturnStringWhenQueryIsProvided()
    {
        $item = new DatabaseItem(
            $this->tableName,
            $this->query,
            new QueryFile(null),
            $this->keepData
        );

        $this->assertSame('CREATE TABLE ps_my_table_2 (id INT) engine=InnoDB', $item->getSQL());
    }

    public function testGetSQLReturnStringWhenQueryFileIsProvided()
    {
        $item = new DatabaseItem(
            $this->tableName,
            new Query(null),
            $this->queryFile,
            $this->keepData
        );

        $this->assertSame('CREATE TABLE ps_my_table_1 (id INT) engine=InnoDB', $item->getSQL());
    }

    public function testGetSQLReturnStringWhenQueryAndQueryFileIsProvided()
    {
        $item = new DatabaseItem(
            $this->tableName,
            $this->query,
            $this->queryFile,
            $this->keepData
        );

        $this->assertSame('CREATE TABLE ps_my_table_1 (id INT) engine=InnoDB', $item->getSQL());
    }
}
