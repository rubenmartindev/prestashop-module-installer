<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Unit\Handler\Database;

use PHPUnit_Framework_MockObject_MockObject;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Database\DatabaseHandlerInstaller;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Database\Exception\FailedToExecuteQueryException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Database\Exception\QueriesIsEmptyException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Database\Exception\QueriesMustBeInstanceOfDatabaseItemException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Database\Item\DatabaseItemInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Stubs\Classes\Db\Db;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Unit\Handler\AbstractHandlerInstallerTestCase;

class DatabaseHandlerInstallerTest extends AbstractHandlerInstallerTestCase
{
    public function testConstructThrowsExceptionWhenQueriesIsEmpty()
    {
        $this->expectException(QueriesIsEmptyException::class);

        new DatabaseHandlerInstaller($this->module, []);
    }

    public function testConstructThrowsExceptionWhenQueriesIsNotInstanceOfDatabaseItemInterface()
    {
        $this->expectException(QueriesMustBeInstanceOfDatabaseItemException::class);

        new DatabaseHandlerInstaller($this->module, [
            'invalidQuery',
        ]);
    }

    public function testConstruct()
    {
        $item1 = $this->createDatabaseItemMock('my_table_1');
        $item2 = $this->createDatabaseItemMock('my_table_2');

        $handler = new DatabaseHandlerInstaller($this->module, [
            $item1,
            $item2,
        ]);

        $this->assertSame($item1, $handler->getQuery('my_table_1'));
        $this->assertSame($item2, $handler->getQuery('my_table_2'));
    }

    public function testAddQuery()
    {
        $item1 = $this->createDatabaseItemMock('my_table_1');
        $item2 = $this->createDatabaseItemMock('my_table_2');

        $handler = new DatabaseHandlerInstaller($this->module, [
            $item1,
        ]);

        $handler->addQuery($item2);

        $this->assertSame($item1, $handler->getQuery('my_table_1'));
        $this->assertSame($item2, $handler->getQuery('my_table_2'));
    }

    public function testGetQueryReturnsNullWhenNotFound()
    {
        $handler = new DatabaseHandlerInstaller($this->module, [
            $this->createDatabaseItemMock('my_table_1'),
        ]);

        $this->assertNull($handler->getQuery('non_existing_query'));
    }

    public function testGetQueryReturnDatabaseItemWhenFound()
    {
        $item = $this->createDatabaseItemMock('my_table');

        $handler = new DatabaseHandlerInstaller($this->module, [
            $item,
        ]);

        $this->assertSame($item, $handler->getQuery('my_table'));
    }

    public function testRemoveQuery()
    {
        $item1 = $this->createDatabaseItemMock('my_table_1');
        $item2 = $this->createDatabaseItemMock('my_table_2');

        $handler = new DatabaseHandlerInstaller($this->module, [
            $item1,
            $item2,
        ]);

        $handler->removeQuery('my_table_1');

        $this->assertNull($handler->getQuery('my_table_1'));
        $this->assertSame($item2, $handler->getQuery('my_table_2'));
    }

    public function testGetQueries()
    {
        $item1 = $this->createDatabaseItemMock('my_table_1');
        $item2 = $this->createDatabaseItemMock('my_table_2');

        $handler = new DatabaseHandlerInstaller($this->module, [
            $item1,
            $item2,
        ]);

        $queries = $handler->getQueries();

        $this->assertSame($item1, $queries['my_table_1']);
        $this->assertSame($item2, $queries['my_table_2']);
    }

    /**
     * @runInSeparateProcess
     */
    public function testInstallThrowsExceptionWhenQueryFails()
    {
        $this->expectException(FailedToExecuteQueryException::class);

        Db::$forceThrowExceptionOnExecute = true;

        $handler = new DatabaseHandlerInstaller($this->module, [
            $this->createDatabaseItemMock('my_table'),
        ]);

        $handler->install();
    }

    public function testInstallReturnsTrue()
    {
        $handler = new DatabaseHandlerInstaller($this->module, [
            $this->createDatabaseItemMock('my_table'),
        ]);

        $this->assertTrue($handler->install());
    }

    /**
     * @runInSeparateProcess
     */
    public function testUninstallThrowsExceptionWhenQueryFails()
    {
        $this->expectException(FailedToExecuteQueryException::class);

        Db::$forceThrowExceptionOnExecute = true;

        $handler = new DatabaseHandlerInstaller($this->module, [
            $this->createDatabaseItemMock('my_table'),
        ]);

        $handler->uninstall();
    }

    public function testUninstallReturnsTrue()
    {
        $handler = new DatabaseHandlerInstaller($this->module, [
            $this->createDatabaseItemMock('my_table'),
        ]);

        $this->assertTrue($handler->uninstall());
    }

    /**
     * @param string $tableName
     *
     * @return DatabaseItemInterface|PHPUnit_Framework_MockObject_MockObject
     */
    private function createDatabaseItemMock($tableName)
    {
        $databaseItem = $this->createMock(DatabaseItemInterface::class);

        $databaseItem->method('getTableName')->willReturn($tableName);

        return $databaseItem;
    }
}
