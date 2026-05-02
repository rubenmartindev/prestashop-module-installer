<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Unit\Handler\Database;

use PHPUnit_Framework_MockObject_MockObject;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Database\DatabaseHandlerInstaller;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Database\Exception\DatabaseHandlerInstallerException;
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
        $databaseItem1 = $this->createDatabaseItemMock('my_table_1');
        $databaseItem2 = $this->createDatabaseItemMock('my_table_2');

        $handler = new DatabaseHandlerInstaller($this->module, [
            $databaseItem1,
            $databaseItem2,
        ]);

        $this->assertSame($databaseItem1, $handler->getQuery('my_table_1'));
        $this->assertSame($databaseItem2, $handler->getQuery('my_table_2'));
    }

    public function testBuildThrowsExceptionWhenKeyTableNameIsMissing()
    {
        $this->expectException(DatabaseHandlerInstallerException::class);
        $this->expectExceptionMessage('The key tableName is required');

        DatabaseHandlerInstaller::build(
            $this->module,
            [
                [
                    'queryFile' => 'my_table.sql',
                ],
            ]
        );
    }

    public function testBuildThrowsExceptionWhenKeyQueryFileIsMissing()
    {
        $this->expectException(DatabaseHandlerInstallerException::class);
        $this->expectExceptionMessage('The key queryFile is required');

        DatabaseHandlerInstaller::build(
            $this->module,
            [
                [
                    'tableName' => 'my_table',
                ],
            ]
        );
    }

    public function testBuild()
    {
        $factory = function (array $query) {
            $query['keepData'] = isset($query['keepData']) ? $query['keepData'] : false;

            return $this->createDatabaseItemMock($query['tableName'], $query['keepData']);
        };

        $handler = DatabaseHandlerInstaller::build(
            $this->module,
            [
                [
                    'tableName' => 'my_table_1',
                    'queryFile' => 'my_table_1.sql',
                ],
                [
                    'tableName' => 'my_table_2',
                    'queryFile' => 'my_table_2.sql',
                    'keepData'  => true,
                ],
            ],
            $factory
        );

        $databaseItem1 = $handler->getQuery('my_table_1');
        $databaseItem2 = $handler->getQuery('my_table_2');

        $this->assertInstanceOf(DatabaseItemInterface::class, $databaseItem1);
        $this->assertInstanceOf(DatabaseItemInterface::class, $databaseItem2);
    }

    public function testAddQuery()
    {
        $databaseItem1 = $this->createDatabaseItemMock('my_table_1');
        $databaseItem2 = $this->createDatabaseItemMock('my_table_2');

        $handler = new DatabaseHandlerInstaller($this->module, [
            $databaseItem1,
        ]);

        $handler->addQuery($databaseItem2);

        $this->assertSame($databaseItem1, $handler->getQuery('my_table_1'));
        $this->assertSame($databaseItem2, $handler->getQuery('my_table_2'));
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
        $databaseItem = $this->createDatabaseItemMock('my_table');

        $handler = new DatabaseHandlerInstaller($this->module, [
            $databaseItem,
        ]);

        $this->assertSame($databaseItem, $handler->getQuery('my_table'));
    }

    public function testRemoveQuery()
    {
        $databaseItem1 = $this->createDatabaseItemMock('my_table_1');
        $databaseItem2 = $this->createDatabaseItemMock('my_table_2');

        $handler = new DatabaseHandlerInstaller($this->module, [
            $databaseItem1,
            $databaseItem2,
        ]);

        $handler->removeQuery('my_table_1');

        $this->assertNull($handler->getQuery('my_table_1'));
        $this->assertSame($databaseItem2, $handler->getQuery('my_table_2'));
    }

    public function testGetQueries()
    {
        $databaseItem1 = $this->createDatabaseItemMock('my_table_1');
        $databaseItem2 = $this->createDatabaseItemMock('my_table_2');

        $handler = new DatabaseHandlerInstaller($this->module, [
            $databaseItem1,
            $databaseItem2,
        ]);

        $queries = $handler->getQueries();

        $this->assertSame($databaseItem1, $queries['my_table_1']);
        $this->assertSame($databaseItem2, $queries['my_table_2']);
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
     * @param bool $keepData
     *
     * @return DatabaseItemInterface|PHPUnit_Framework_MockObject_MockObject
     */
    private function createDatabaseItemMock($tableName, $keepData = false)
    {
        $databaseItem = $this->createMock(DatabaseItemInterface::class);

        $databaseItem->method('getTableName')->willReturn($tableName);
        $databaseItem->method('getKeepData')->willReturn($keepData);

        return $databaseItem;
    }
}
