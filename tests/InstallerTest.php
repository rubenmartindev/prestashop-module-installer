<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests;

use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit\Framework\TestCase;
use RubenMartinDev\PrestaShopModuleInstaller\Exception\InstallerException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\HandlerInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Installer;
use RubenMartinDev\PrestaShopModuleInstaller\InstallerInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Resources\Handler\CustomHandler;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Resources\ModuleTrait;
use stdClass;

class InstallerTest extends TestCase
{
    use ModuleTrait;

    public function testConstructorThrowsExceptionWhenHandlersDoesNotImplementHandlerInterface()
    {
        $this->expectException(InstallerException::class);

        new Installer([new stdClass()]);
    }

    public function testConstructorReturnsInstaller()
    {
        $installer = new Installer([
            $this->createHandlerMock(),
            CustomHandler::createFrom($this->getModule(), [])
        ]);

        $this->assertInstanceOf(InstallerInterface::class, $installer);
    }

    public function testCreateFromThrowsExceptionWhenHandlerDoesNotImplementHandlerInterface()
    {
        $this->expectException(InstallerException::class);

        Installer::createFrom($this->getModule(), [new stdClass()]);
    }

    public function testCreateFromReturnInstaller()
    {
        $installer = Installer::createFrom(
            $this->getModule(),
            [
                'configuration'         => [
                    [
                        'name'              => 'my_configuration',
                    ]
                ],
                'database'              => [
                    [
                        'tableName'         => 'my_table',
                    ],
                ],
                'hooks'                 => [
                    [
                        'name'              => 'displayHeader',
                    ],
                ],
                'tabs'                  => [
                    [
                        'className'         => 'AdminMyModule',
                        'name'              => 'My tab'
                    ],
                ],
                CustomHandler::class    => [
                    [
                        'foo'               => 'bar',
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
            CustomHandler::createFrom($this->getModule(), []),
        ]);

        $this->assertTrue($installer->install());
    }

    public function testUninstall()
    {
        $installer = new Installer([
            $this->createHandlerMock(),
            CustomHandler::createFrom($this->getModule(), []),
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
