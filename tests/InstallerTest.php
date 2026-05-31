<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests;

use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit\Framework\TestCase;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\HandlerInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Installer;
use RubenMartinDev\PrestaShopModuleInstaller\InstallerInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Resources\ModuleTrait;

class InstallerTest extends TestCase
{
    use ModuleTrait;

    public function testConstructorReturnsInstaller()
    {
        $installer1 = new Installer([]);
        $installer2 = new Installer([$this->createHandlerMock()]);

        $this->assertInstanceOf(InstallerInterface::class, $installer1);
        $this->assertInstanceOf(InstallerInterface::class, $installer2);
    }

    public function testCreateFromReturnInstallerWithoutHandlers()
    {
        $installer = Installer::createFrom(
            $this->getModule(),
            []
        );

        $this->assertInstanceOf(InstallerInterface::class, $installer);
    }

    public function testCreateFromReturnInstallerWithHandlers()
    {
        $installer = Installer::createFrom(
            $this->getModule(),
            [
                'configuration' => [
                    [
                        'name'      => 'my_configuration',
                    ]
                ],
                'database'      => [
                    [
                        'tableName' => 'my_table',
                    ],
                ],
                'hooks'         => [
                    [
                        'name'      => 'displayHeader',
                    ],
                ],
                'tabs'          => [
                    [
                        'className' => 'AdminMyModule',
                        'name'      => 'My tab'
                    ],
                ],
                'foobar'        => [
                    [
                        'baar' => true,
                    ],
                ],
            ]
        );

        $this->assertInstanceOf(InstallerInterface::class, $installer);
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
