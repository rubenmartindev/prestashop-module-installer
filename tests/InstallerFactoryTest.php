<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests;

use Module;
use org\bovigo\vfs\vfsStream;
use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit\Framework\TestCase;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\HandlerInstallerInterface;
use RubenMartinDev\PrestaShopModuleInstaller\InstallerFactory;
use RubenMartinDev\PrestaShopModuleInstaller\InstallerInterface;

class InstallerFactoryTest extends TestCase
{
    public function testCreateReturnsInstallerWithoutCallbacks()
    {
        $directory = vfsStream::setup();

        vfsStream::newFile('my_table.sql')
            ->withContent('CREATE TABLE IF NOT EXISTS `{{DB_PREFIX}}my_table` (id INT) ENGINE={{ENGINE_TYPE}};')
            ->at($directory)
        ;

        $installer = InstallerFactory::create(
            $this->createModuleMock(),
            [
                'database'  => [
                    [
                        'tableName' => 'my_table',
                        'queryFile' => vfsStream::url('root/my_table.sql'),
                    ],
                ],
                'hooks'     => [
                    [
                        'name'      => 'displayHeader',
                    ],
                ],
                'tabs'      => [
                    [
                        'className' => 'AdminMyModule',
                        'name'      => 'My tab'
                    ],
                ],
            ]
        );

        $this->assertInstanceOf(InstallerInterface::class, $installer);

        $handler1 = $installer->getHandler(0);
        $handler2 = $installer->getHandler(1);
        $handler3 = $installer->getHandler(2);

        $this->assertInstanceOf(HandlerInstallerInterface::class, $handler1);
        $this->assertInstanceOf(HandlerInstallerInterface::class, $handler2);
        $this->assertInstanceOf(HandlerInstallerInterface::class, $handler3);
    }

    public function testCreateReturnsInstallerWithCallbacks()
    {
        $factoryDatabase = function () {
            return $this->createHandlerInstallerMock();
        };
        $factoryHooks = function () {
            return $this->createHandlerInstallerMock();
        };
        $factoryTabs = function () {
            return $this->createHandlerInstallerMock();
        };

        $installer = InstallerFactory::create(
            $this->createModuleMock(),
            [
                'database'  => [],
                'hooks'     => [],
                'tabs'      => [],
            ],
            $factoryDatabase,
            $factoryHooks,
            $factoryTabs
        );

        $this->assertInstanceOf(InstallerInterface::class, $installer);

        $handler1 = $installer->getHandler(0);
        $handler2 = $installer->getHandler(1);
        $handler3 = $installer->getHandler(2);

        $this->assertInstanceOf(HandlerInstallerInterface::class, $handler1);
        $this->assertInstanceOf(HandlerInstallerInterface::class, $handler2);
        $this->assertInstanceOf(HandlerInstallerInterface::class, $handler3);
    }

    /**
     * @return HandlerInstallerInterface|PHPUnit_Framework_MockObject_MockObject
     */
    private function createHandlerInstallerMock()
    {
        $handler = $this->createMock(HandlerInstallerInterface::class);

        $handler->method('install')->willReturn(true);
        $handler->method('uninstall')->willReturn(true);

        return $handler;
    }

    /**
     * @return Module|PHPUnit_Framework_MockObject_MockObject
     */
    private function createModuleMock()
    {
        return $this->getMockForAbstractClass(Module::class);
    }
}
