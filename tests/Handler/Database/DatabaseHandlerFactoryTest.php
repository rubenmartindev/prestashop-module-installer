<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Handler\Database;

use org\bovigo\vfs\vfsStream;
use PHPUnit_Framework_MockObject_MockObject;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Database\DatabaseHandlerFactory;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Database\Item\DatabaseItemInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Database\Item\Exception\QuerFileNotExistsException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Database\Item\Exception\TableNameIsEmptyException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\HandlerInstallerInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Handler\AbstractHandlerInstallerTestCase;

class DatabaseHandlerFactoryTest extends AbstractHandlerInstallerTestCase
{
    public function testCreateThrowsExceptionWhenKeyTableNameIsMissing()
    {
        $this->expectException(TableNameIsEmptyException::class);

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
        $this->expectException(QuerFileNotExistsException::class);

        DatabaseHandlerFactory::create(
            $this->module,
            [
                [
                    'tableName' => 'my_table',
                ],
            ]
        );
    }

    public function testCreateReturnsHandlerInstallerWithFactory()
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

        $this->assertInstanceOf(HandlerInstallerInterface::class, $handler);

        $databaseItem1 = $handler->getQuery('my_table_1');
        $databaseItem2 = $handler->getQuery('my_table_2');

        $this->assertInstanceOf(DatabaseItemInterface::class, $databaseItem1);
        $this->assertInstanceOf(DatabaseItemInterface::class, $databaseItem2);
    }

    public function testCreateReturnsHandlerInstallerWithoutFactory()
    {
        $directory = vfsStream::setup();

        vfsStream::newFile('my_table_1.sql')
            ->withContent('CREATE TABLE IF NOT EXISTS `{{DB_PREFIX}}my_table` (id INT) ENGINE={{ENGINE_TYPE}};')
            ->at($directory)
        ;

        vfsStream::newFile('my_table_2.sql')
            ->withContent('CREATE TABLE IF NOT EXISTS `{{DB_PREFIX}}my_table` (id INT) ENGINE={{ENGINE_TYPE}};')
            ->at($directory)
        ;

        $handler = DatabaseHandlerFactory::create(
            $this->module,
            [
                [
                    'tableName' => 'my_table_1',
                    'queryFile' => vfsStream::url('root/my_table_1.sql'),
                ],
                [
                    'tableName' => 'my_table_2',
                    'queryFile' => vfsStream::url('root/my_table_2.sql'),
                    'keepData'  => true,
                ],
            ]
        );

        $this->assertInstanceOf(HandlerInstallerInterface::class, $handler);

        $tabItem1 = $handler->getQuery('my_table_1');
        $tabItem2 = $handler->getQuery('my_table_2');

        $this->assertInstanceOf(DatabaseItemInterface::class, $tabItem1);
        $this->assertInstanceOf(DatabaseItemInterface::class, $tabItem2);
    }

    /**
     * @param string $tableName
     * @param bool $keepData
     *
     * @return DatabaseItemInterface&PHPUnit_Framework_MockObject_MockObject
     */
    private function createDatabaseItemMock($tableName, $keepData = false)
    {
        $databaseItem = $this->createMock(DatabaseItemInterface::class);

        $databaseItem->method('getTableName')->willReturn($tableName);
        $databaseItem->method('getKeepData')->willReturn($keepData);

        return $databaseItem;
    }
}
