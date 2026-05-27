<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests;

use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit\Framework\TestCase;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\HandlerInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Installer;
use RubenMartinDev\PrestaShopModuleInstaller\InstallerInterface;

class InstallerTest extends TestCase
{
    public function testConstructorReturnsInstaller()
    {
        $installer1 = new Installer([]);
        $installer2 = new Installer([$this->createHandlerMock()]);

        $this->assertInstanceOf(InstallerInterface::class, $installer1);
        $this->assertInstanceOf(InstallerInterface::class, $installer2);
    }

    public function testInstall()
    {
        $installer = new Installer([
            $this->createHandlerMock(),
            $this->createHandlerMock(),
        ]);

        $this->assertTrue($installer->install());
    }

    public function testUninstall()
    {
        $installer = new Installer([
            $this->createHandlerMock(),
            $this->createHandlerMock(),
        ]);

        $this->assertTrue($installer->uninstall());
    }

    /**
     * @return HandlerInterface|PHPUnit_Framework_MockObject_MockObject
     */
    private function createHandlerMock()
    {
        $handler = $this->createMock(HandlerInterface::class);

        $handler->method('install')->willReturn(true);
        $handler->method('uninstall')->willReturn(true);

        return $handler;
    }
}
