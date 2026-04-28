<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Unit;

use ArrayIterator;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\InstallerHandlerInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Installer;

class InstallerTest extends TestCase
{
    public function testConstructorWithoutParameters()
    {
        $installer = new Installer();

        $this->assertSame([], $installer->getHandlers());
    }

    public function testConstructorWithArray()
    {
        $handler1 = $this->createMockInstallerHandler();
        $handler2 = $this->createMockInstallerHandler();

        $installer = new Installer([$handler1, 3 => $handler2]);

        $this->assertSame($handler1, $installer->getHandler(0));
        $this->assertSame($handler2, $installer->getHandler(3));
    }

    public function testConstructorWithIterator()
    {
        $handler1 = $this->createMockInstallerHandler();
        $handler2 = $this->createMockInstallerHandler();

        $iterator = new ArrayIterator([$handler1, $handler2]);

        $installer = new Installer($iterator);

        $this->assertSame($handler1, $installer->getHandler(0));
        $this->assertSame($handler2, $installer->getHandler(1));
    }

    public function testAddHandler()
    {
        $installer = new Installer();

        $handler = $this->createMockInstallerHandler();

        $result = $installer->addHandler(0, $handler);

        $this->assertSame($installer, $result);
        $this->assertSame($handler, $installer->getHandler(0));
    }

    public function testAddHandlerOverwritesHandlers()
    {
        $installer = new Installer();

        $handler = $this->createMockInstallerHandler();

        $handlerOverwrite1 = $this->createMockInstallerHandler();
        $handlerOverwrite2 = $this->createMockInstallerHandler();

        $installer->addHandler(0, $handler);
        $installer->addHandler(1, $handlerOverwrite1);
        $installer->addHandler(1, $handlerOverwrite2);

        $this->assertCount(2, $installer->getHandlers());
        $this->assertSame($handler, $installer->getHandler(0));
        $this->assertSame($handlerOverwrite2, $installer->getHandler(1));
    }

    public function testGetHandlerReturnsNullWhenNotFound()
    {
        $installer = new Installer();

        $this->assertNull($installer->getHandler(0));
    }

    public function testGetHandlerReturnsHandlerWhenFound()
    {
        $installer = new Installer();

        $handler = $this->createMockInstallerHandler();

        $installer->addHandler(0, $handler);

        $this->assertSame($handler, $installer->getHandler(0));
    }

    public function testRemoveHandlerWhenHandlerNotFound()
    {
        $installer = new Installer();

        $result = $installer->removeHandler(0);

        $this->assertSame($installer, $result);
    }

    public function testRemoveHandlerWhenHandlerFound()
    {
        $installer = new Installer();

        $handler = $this->createMockInstallerHandler();

        $installer->addHandler(0, $handler);

        $result = $installer->removeHandler(0);

        $this->assertSame($installer, $result);
        $this->assertNull($installer->getHandler(0));
    }

    public function testGetHandlersReturnsEmptyArrayWhenNoHandlers()
    {
        $installer = new Installer();

        $this->assertSame([], $installer->getHandlers());
    }

    public function testGetHandlersReturnsAllHandlers()
    {
        $installer = new Installer();

        $handler1 = $this->createMockInstallerHandler();
        $handler2 = $this->createMockInstallerHandler();

        $installer->addHandler(0, $handler1);
        $installer->addHandler(1, $handler2);

        $handlers = $installer->getHandlers();

        $this->assertCount(2, $handlers);
        $this->assertSame($handler1, $handlers[0]);
        $this->assertSame($handler2, $handlers[1]);
    }

    /**
     * @return InstallerHandlerInterface|PHPUnit_Framework_MockObject_MockObject
     */
    private function createMockInstallerHandler()
    {
        $handler = $this->createMock(InstallerHandlerInterface::class);

        return $handler;
    }
}
