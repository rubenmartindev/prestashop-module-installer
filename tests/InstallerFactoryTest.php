<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests;

use PHPUnit\Framework\TestCase;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\HandlerInterface;
use RubenMartinDev\PrestaShopModuleInstaller\InstallerFactory;
use RubenMartinDev\PrestaShopModuleInstaller\InstallerInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Resources\ModuleTrait;

class InstallerFactoryTest extends TestCase
{
    use ModuleTrait;

    public function testCreateReturnsInstallerWithFactories()
    {
        $factoryDatabase = function () {
            return $this->createMock(HandlerInterface::class);
        };
        $factoryHooks = function () {
            return $this->createMock(HandlerInterface::class);
        };
        $factoryTabs = function () {
            return $this->createMock(HandlerInterface::class);
        };

        $installer = InstallerFactory::create(
            $this->getModule(),
            [
                'database'  => ['foobar'],
                'hooks'     => ['foobar'],
                'tabs'      => ['foobar'],
            ],
            $factoryDatabase,
            $factoryHooks,
            $factoryTabs
        );

        $this->assertInstanceOf(InstallerInterface::class, $installer);
    }

    public function testCreateReturnsInstallerWithoutFactories()
    {
        $installer = InstallerFactory::create(
            $this->getModule(),
            [
                'database'  => [
                    [
                        'tableName' => 'my_table',
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
    }
}
