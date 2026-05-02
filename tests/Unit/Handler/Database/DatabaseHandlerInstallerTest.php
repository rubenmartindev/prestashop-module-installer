<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Unit\Handler\Database;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamContainer;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Database\DatabaseHandlerInstaller;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Database\Exception\FailedToExecuteQueryException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Database\Exception\QueriesIsEmptyException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Database\Exception\QueriesMustBeInstanceOfDatabaseItemException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Database\Item\DatabaseItem;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Stubs\Classes\Db\Db;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Unit\Handler\AbstractHandlerInstallerTestCase;

class DatabaseHandlerInstallerTest extends AbstractHandlerInstallerTestCase
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

        vfsStream::newFile('my_table_1.sql')
            ->withContent('CREATE TABLE IF NOT EXISTS `{{DB_PREFIX}}my_table_1` (id INT) ENGINE={{ENGINE_TYPE}};')
            ->at($this->directory)
        ;

        vfsStream::newFile('my_table_2.sql')
            ->withContent('CREATE TABLE IF NOT EXISTS `{{DB_PREFIX}}my_table_2` (id INT) ENGINE={{ENGINE_TYPE}};')
            ->at($this->directory)
        ;
    }

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
        $item1 = new DatabaseItem('my_table_1', vfsStream::url('root/my_table_1.sql'));
        $item2 = new DatabaseItem('my_table_2', vfsStream::url('root/my_table_2.sql'));

        $handler = new DatabaseHandlerInstaller($this->module, [
            $item1,
            $item2,
        ]);

        $this->assertSame($item1, $handler->getQuery('my_table_1'));
        $this->assertSame($item2, $handler->getQuery('my_table_2'));
    }

    public function testAddQuery()
    {
        $item1 = new DatabaseItem('my_table_1', vfsStream::url('root/my_table_1.sql'));
        $item2 = new DatabaseItem('my_table_2', vfsStream::url('root/my_table_2.sql'));

        $handler = new DatabaseHandlerInstaller($this->module, [
            $item1,
        ]);

        $handler->addQuery($item2);

        $this->assertSame($item1, $handler->getQuery('my_table_1'));
        $this->assertSame($item2, $handler->getQuery('my_table_2'));
    }

    public function testGetQueryReturnsNullWhenQueryNotFound()
    {
        $handler = new DatabaseHandlerInstaller($this->module, [
            new DatabaseItem('my_table_1', vfsStream::url('root/my_table_1.sql')),
        ]);

        $this->assertNull($handler->getQuery('non_existing_query'));
    }

    public function testGetQueryReturnDatabaseItemWhenFound()
    {
        $item = new DatabaseItem('my_table_1', vfsStream::url('root/my_table_1.sql'));

        $handler = new DatabaseHandlerInstaller($this->module, [
            $item,
        ]);

        $this->assertSame($item, $handler->getQuery('my_table_1'));
    }

    public function testRemoveQuery()
    {
        $item1 = new DatabaseItem('my_table_1', vfsStream::url('root/my_table_1.sql'));
        $item2 = new DatabaseItem('my_table_2', vfsStream::url('root/my_table_2.sql'));

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
        $item1 = new DatabaseItem('my_table_1', vfsStream::url('root/my_table_1.sql'));
        $item2 = new DatabaseItem('my_table_2', vfsStream::url('root/my_table_2.sql'));

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
            new DatabaseItem('my_table_1', vfsStream::url('root/my_table_1.sql')),
        ]);

        $handler->install();
    }

    public function testInstallReturnsTrue()
    {
        $handler = new DatabaseHandlerInstaller($this->module, [
            new DatabaseItem('my_table_1', vfsStream::url('root/my_table_1.sql')),
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
            new DatabaseItem('my_table_1', vfsStream::url('root/my_table_1.sql')),
        ]);

        $handler->uninstall();
    }

    public function testUninstallReturnsTrue()
    {
        $handler = new DatabaseHandlerInstaller($this->module, [
            new DatabaseItem('my_table_1', vfsStream::url('root/my_table_1.sql')),
        ]);

        $this->assertTrue($handler->uninstall());
    }
}
