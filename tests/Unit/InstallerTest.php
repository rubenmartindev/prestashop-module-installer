<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Unit;

use Module;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\HandlerInstallerInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Installer;

class InstallerTest extends TestCase
{
    public function testConstructor()
    {
        $handler = $this->createHandlerInstallerMock();

        $installer = new Installer([
            $handler,
        ]);

        $this->assertSame($handler, $installer->getHandler(0));
    }

    public function testBuild()
    {
        $factoryDatabase = function (Module $module, array $handler) {
            return $this->createHandlerInstallerMock();
        };
        $factoryHooks = function (Module $module, array $handler) {
            return $this->createHandlerInstallerMock();
        };
        $factoryTabs = function (Module $module, array $handler) {
            return $this->createHandlerInstallerMock();
        };

        $installer = Installer::build(
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

        $handler1 = $installer->getHandler(0);
        $handler2 = $installer->getHandler(1);
        $handler3 = $installer->getHandler(2);

        $this->assertInstanceOf(HandlerInstallerInterface::class, $handler1);
        $this->assertInstanceOf(HandlerInstallerInterface::class, $handler2);
        $this->assertInstanceOf(HandlerInstallerInterface::class, $handler3);
    }

    public function testAddHandler()
    {
        $handler1 = $this->createHandlerInstallerMock();
        $handler2 = $this->createHandlerInstallerMock();

        $installer = new Installer([
            $handler1,
        ]);

        $installer->addHandler(1, $handler2);

        $this->assertSame($handler1, $installer->getHandler(0));
        $this->assertSame($handler2, $installer->getHandler(1));
    }

    public function testGetHandlerReturnsNullWhenNotFound()
    {
        $installer = new Installer([
            $this->createHandlerInstallerMock(),
        ]);

        $this->assertNull($installer->getHandler(-1));
    }

    public function testGetHandlerReturnsHandlerWhenFound()
    {
        $handler = $this->createHandlerInstallerMock();

        $installer = new Installer([
            $handler,
        ]);

        $this->assertSame($handler, $installer->getHandler(0));
    }

    public function testRemoveHandler()
    {
        $handler1 = $this->createHandlerInstallerMock();
        $handler2 = $this->createHandlerInstallerMock();

        $installer = new Installer([
            $handler1,
            $handler2,
        ]);

        $installer->removeHandler(1);

        $this->assertSame($handler1, $installer->getHandler(0));
        $this->assertNull($installer->getHandler(1));
    }

    public function testGetHandlers()
    {
        $handler1 = $this->createHandlerInstallerMock();
        $handler2 = $this->createHandlerInstallerMock();

        $installer = new Installer([
            $handler1,
            $handler2,
        ]);

        $handlers = $installer->getHandlers();

        $this->assertCount(2, $handlers);
        $this->assertSame($handler1, $handlers[0]);
        $this->assertSame($handler2, $handlers[1]);
    }

    public function testInstall()
    {
        $installer = new Installer([
            $this->createHandlerInstallerMock(),
            $this->createHandlerInstallerMock(),
        ]);

        $this->assertTrue($installer->install());
    }

    public function testUninstall()
    {
        $installer = new Installer([
            $this->createHandlerInstallerMock(),
            $this->createHandlerInstallerMock(),
        ]);

        $this->assertTrue($installer->uninstall());
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
