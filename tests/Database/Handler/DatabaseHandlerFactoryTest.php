<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Database\Handler;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamContainer;
use PHPUnit\Framework\TestCase;
use RubenMartinDev\PrestaShopModuleInstaller\Database\Handler\DatabaseHandlerFactory;
use RubenMartinDev\PrestaShopModuleInstaller\Database\Handler\DatabaseHandlerInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Database\Item\DatabaseItemInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Resources\ModuleTrait;

final class DatabaseHandlerFactoryTest extends TestCase
{
    use ModuleTrait;

    /** @var vfsStreamContainer */
    private $directory;

    protected function setUp()
    {
        parent::setUp();

        $this->directory = vfsStream::setup();

        vfsStream::newFile('my_table_2.sql')
            ->withContent('CREATE TABLE {{DB_PREFIX}}my_table_2 (id INT) engine={{ENGINE_TYPE}}')
            ->at($this->directory)
        ;
    }

    public function testCreateReturnsHandlerInstallerWithFactory()
    {
        $factory = function (array $item) {
            return $this->createMock(DatabaseItemInterface::class);
        };

        $handler = DatabaseHandlerFactory::create(
            $this->getModule(),
            [
                [
                    'tableName' => 'my_table_1'
                ],
                [
                    'tableName' => 'my_table_2',
                    'query'     => 'CREATE TABLE my_table_2',
                    'queryFile' => vfsStream::url('root/my_table_2.sql'),
                    'keepData'  => true,
                ],
            ],
            $factory
        );

        $this->assertInstanceOf(DatabaseHandlerInterface::class, $handler);
    }

    public function testCreateReturnsDatabaseHandlerWithoutFactory()
    {
        $handler = DatabaseHandlerFactory::create(
            $this->getModule(),
            [
                [
                    'tableName' => 'my_table_1'
                ],
                [
                    'tableName' => 'my_table_2',
                    'query'     => 'CREATE TABLE my_table_2',
                    'queryFile' => vfsStream::url('root/my_table_2.sql'),
                    'keepData'  => true,
                ],
            ]
        );

        $this->assertInstanceOf(DatabaseHandlerInterface::class, $handler);
    }
}
