<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Handler\Database;

use PHPUnit_Framework_MockObject_MockObject;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Database\DatabaseHandlerFactory;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Database\Exception\DatabaseHandlerException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Database\Item\DatabaseItemInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Handler\AbstractHandlerInstallerTestCase;

class DatabaseHandlerFactoryTest extends AbstractHandlerInstallerTestCase
{

    public function testCreateThrowsExceptionWhenKeyTableNameIsMissing()
    {
        $this->expectException(DatabaseHandlerException::class);
        $this->expectExceptionMessage('The key tableName is required');

        DatabaseHandlerFactory::create(
            $this->module,
            [
                [
                    'queryFile' => 'my_table.sql',
                ],
            ]
        );
    }

    public function testCreateThrowsExceptionWhenKeyQueryFileIsMissing()
    {
        $this->expectException(DatabaseHandlerException::class);
        $this->expectExceptionMessage('The key queryFile is required');

        DatabaseHandlerFactory::create(
            $this->module,
            [
                [
                    'tableName' => 'my_table',
                ],
            ]
        );
    }

    public function testCreate()
    {
        $factory = function (array $query) {
            $query['keepData'] = isset($query['keepData']) ? $query['keepData'] : false;

            return $this->createDatabaseItemMock($query['tableName'], $query['keepData']);
        };

        $handler = DatabaseHandlerFactory::create(
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
