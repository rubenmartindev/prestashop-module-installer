<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Database\Item;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamContainer;
use PHPUnit\Framework\TestCase;

final class DatabaseItemFactoryTest extends TestCase
{
    /** @var vfsStreamContainer */
    private $directory;

    protected function setUp()
    {
        parent::setUp();

        $this->directory = vfsStream::setup();

        vfsStream::newFile('my_table.sql')
            ->at($this->directory)
        ;
    }

    public function testCreateRetursDatabaseItemWithRequireParameters()
    {
        $item = DatabaseItemFactory::create('my_table');

        $this->assertInstanceOf(DatabaseItemInterface::class, $item);
    }

    public function testCreateRetursDatabaseItemWithOptionalParameters()
    {
        $item = DatabaseItemFactory::create(
            'my_table',
            'CREATE TABLE my_table',
            vfsStream::url('root/my_table.sql'),
            false
        );

        $this->assertInstanceOf(DatabaseItemInterface::class, $item);
    }
}
