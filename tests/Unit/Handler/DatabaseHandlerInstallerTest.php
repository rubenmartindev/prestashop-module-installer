<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Unit\Handler;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\DatabaseHandlerInstaller;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Exception\DatabaseHandlerInstallerException;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Stubs\Classes\Db\Db;

class DatabaseHandlerInstallerTest extends AbstractHandlerInstallerTestCase
{
    /** @var vfsStreamDirectory */
    private $vfsDirectory;

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

        $this->vfsDirectory = vfsStream::setup();
    }

    public function testInstallReturnsTrueWhenWithoutQueries()
    {
        $handler = new DatabaseHandlerInstaller($this->module, []);

        $this->assertTrue($handler->install());
    }

    public function testInstallReturnsTrueWhenWithQueries()
    {
        vfsStream::newFile('create_table.sql', 0644)
            ->withContent('CREATE TABLE IF NOT EXISTS `{{DB_PREFIX}}my_table` (id INT) ENGINE={{ENGINE_TYPE}};')
            ->at($this->vfsDirectory)
        ;

        $handler = new DatabaseHandlerInstaller($this->module, [
            'my_table' => [
                'query' => 'CREATE TABLE IF NOT EXISTS `{{DB_PREFIX}}my_table` (id INT) ENGINE={{ENGINE_TYPE}};',
            ],
            'my_other_table' => [
                'file' => vfsStream::url('root/create_table.sql'),
            ],
        ]);

        $this->assertTrue($handler->install());
    }

    /**
     * @runInSeparateProcess
     */
    public function testInstallThrowsExceptionWhenQueryFails()
    {
        $this->expectException(DatabaseHandlerInstallerException::class);

        Db::$forceThrowExceptionOnExecute = true;

        $handler = new DatabaseHandlerInstaller($this->module, [
            'my_table' => [
                'query' => 'CREATE TABLE IF NOT EXISTS `{{DB_PREFIX}}my_table` (id INT) ENGINE={{ENGINE_TYPE}};',
            ],
        ]);

        $handler->install();
    }

    public function testInstallThrowsExceptionWhenSQLFileIsNotFound()
    {
        $this->expectException(DatabaseHandlerInstallerException::class);

        $handler = new DatabaseHandlerInstaller($this->module, [
            'my_table' => [
                'file' => vfsStream::url('root/non_existent_file.sql'),
            ],
        ]);

        $handler->install();
    }

    public function testInstallThrowsExceptionWhenSQLFileIsNotReadable()
    {
        vfsStream::newFile('no_readable.sql', 0000)
            ->withContent('CREATE TABLE IF NOT EXISTS `{{DB_PREFIX}}my_table` (id INT) ENGINE={{ENGINE_TYPE}};')
            ->at($this->vfsDirectory)
        ;

        $handler = new DatabaseHandlerInstaller($this->module, [
            'my_table' => [
                'file' => vfsStream::url('root/no_readable.sql'),
            ],
        ]);

        $this->expectException(DatabaseHandlerInstallerException::class);

        $handler->install();
    }

    public function testInstallThrowsExceptionWhenSQLFileIsEmpty()
    {
        vfsStream::newFile('empty.sql', 0644)
            ->withContent('')
            ->at($this->vfsDirectory)
        ;

        $handler = new DatabaseHandlerInstaller($this->module, [
            'my_table' => [
                'file' => vfsStream::url('root/empty.sql'),
            ],
        ]);

        $this->expectException(DatabaseHandlerInstallerException::class);

        $handler->install();
    }

    public function testUninstallReturnsTrueWhenWithoutQueries()
    {
        $handler = new DatabaseHandlerInstaller($this->module, []);

        $this->assertTrue($handler->uninstall());
    }

    public function testUninstallReturnsTrueWhenWithQueries()
    {
        $handler = new DatabaseHandlerInstaller($this->module, [
            'my_table' => [
                'keep_data' => false,
            ],
            'my_other_table' => [
                'keep_data' => true,
            ],
        ]);

        $this->assertTrue($handler->uninstall());
    }

    /**
     * @runInSeparateProcess
     */
    public function testUninstallThrowsExceptionWhenQueryFails()
    {
        $this->expectException(DatabaseHandlerInstallerException::class);

        Db::$forceThrowExceptionOnExecute = true;

        $handler = new DatabaseHandlerInstaller($this->module, [
            'my_table' => [
                'keep_data' => false,
            ],
        ]);

        $handler->uninstall();
    }
}
