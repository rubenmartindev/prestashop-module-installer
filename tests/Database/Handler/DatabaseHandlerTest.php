<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Database\Handler;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamContainer;
use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit\Framework\TestCase;
use RubenMartinDev\PrestaShopModuleInstaller\Database\Handler\DatabaseHandler;
use RubenMartinDev\PrestaShopModuleInstaller\Database\Handler\DatabaseHandlerInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Database\Handler\Exception\FailedToExecuteQueryException;
use RubenMartinDev\PrestaShopModuleInstaller\Database\Item\DatabaseItemInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Database\ValueObject\KeepData;
use RubenMartinDev\PrestaShopModuleInstaller\Database\ValueObject\TableName;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Exception\ItemTypeIsInvalidException;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Resources\ModuleTrait;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Resources\Stubs\Classes\Db\DbStub;

final class DatabaseHandlerTest extends TestCase
{
    use ModuleTrait;

    /** @var vfsStreamContainer */
    private $directory;

    protected function setUp()
    {
        parent::setUp();

        $this->directory = vfsStream::setup();

        vfsStream::newFile('my_table.sql')
            ->withContent('CREATE TABLE my_table')
            ->at($this->directory)
        ;
    }

    public function testConstructThrowsExceptionWhenItemsIsInvalid()
    {
        $this->expectException(ItemTypeIsInvalidException::class);

        new DatabaseHandler($this->getModule(), ['foobar']);
    }

    public function testConstructReturnsHandler()
    {
        $handler = new DatabaseHandler($this->getModule(), [$this->createItemMock('my_table')]);

        $this->assertInstanceOf(DatabaseHandlerInterface::class, $handler);
    }

    public function testCreateFromReturnHandlerWithRequireParameters()
    {
        $handler = Databasehandler::createFrom(
            $this->getModule(),
            [
                [
                    'table_name' => 'my_table_1',
                ],
            ]
        );

        $this->assertInstanceOf(DatabaseHandlerInterface::class, $handler);
    }

    public function testCreateFromReturnHandlerWithOptionalParameters()
    {
        $handler = Databasehandler::createFrom(
            $this->getModule(),
            [
                [
                    'table_name'    => 'my_table',
                    'query'         => 'CREATE TABLE my_table',
                    'query_file'    => vfsStream::url('root/my_table.sql'),
                    'keep_data'     => true,
                ],
            ]
        );

        $this->assertInstanceOf(DatabaseHandlerInterface::class, $handler);
    }

    public function testGetItemsReturnsItems()
    {
        $item = $this->createItemMock('my_table');

        $handler = new DatabaseHandler(
            $this->getModule(),
            [$item]
        );

        $this->assertSame([$item], $handler->getItems());
    }

    public function testAddItemAddsItem()
    {
        $item1 = $this->createItemMock('my_table');
        $item2 = $this->createItemMock('my_other_table');

        $handler = new DatabaseHandler(
            $this->getModule(),
            [$item1]
        );

        $result = $handler->addItem($item2, 5);

        $this->assertSame([0 => $item1, 5 => $item2], $handler->getItems());
        $this->assertSame($result, $handler);
    }

    public function testRemoveItemRemovesItem()
    {
        $item1 = $this->createItemMock('my_table');
        $item2 = $this->createItemMock('my_other_table');

        $handler = new DatabaseHandler(
            $this->getModule(),
            [$item1, $item2]
        );

        $result = $handler->removeItem(0);

        $this->assertSame([1 => $item2], $handler->getItems());
        $this->assertSame($result, $handler);
    }

    /**
     * @runInSeparateProcess
     */
    public function testInstallThrowsExceptionWhenQueryFails()
    {
        $this->expectException(FailedToExecuteQueryException::class);

        DbStub::$forceThrowExceptionOnExecute = true;

        $handler = new DatabaseHandler(
            $this->getModule(),
            [$this->createItemMock('my_table')]
        );

        $handler->install();
    }

    public function testInstallReturnsTrue()
    {
        $handler = new DatabaseHandler(
            $this->getModule(),
            [$this->createItemMock('my_table')]
        );

        $this->assertTrue($handler->install());
    }

    /**
     * @runInSeparateProcess
     */
    public function testUninstallThrowsExceptionWhenQueryFails()
    {
        $this->expectException(FailedToExecuteQueryException::class);

        DbStub::$forceThrowExceptionOnExecute = true;

        $handler = new DatabaseHandler(
            $this->getModule(),
            [$this->createItemMock('my_table')]
        );

        $handler->uninstall();
    }

    public function testUninstallReturnsTrue()
    {
        $handler = new DatabaseHandler(
            $this->getModule(),
            [$this->createItemMock('my_table')]
        );

        $this->assertTrue($handler->uninstall());
    }

    /**
     * @param string $tableName
     * @param bool $keepData
     *
     * @return DatabaseItemInterface|PHPUnit_Framework_MockObject_MockObject
     */
    private function createItemMock($tableName, $keepData = false)
    {
        $item = $this->createMock(DatabaseItemInterface::class);

        $item->method('getTableName')->willReturn(new TableName($tableName));
        $item->method('getKeepData')->willReturn(new KeepData($keepData));
        $item->method('getSQL')->willReturn("CREATE TABLE `{$tableName}`");

        return $item;
    }
}
