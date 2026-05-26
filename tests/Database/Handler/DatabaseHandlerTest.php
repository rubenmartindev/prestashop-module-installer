<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Database\Handler;

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
