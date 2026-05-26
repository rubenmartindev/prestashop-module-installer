<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Hook\Handler;

use PHPUnit\Framework\TestCase;
use RubenMartinDev\PrestaShopModuleInstaller\Hook\Handler\HookHandlerFactory;
use RubenMartinDev\PrestaShopModuleInstaller\Hook\Handler\HookHandlerInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Hook\Item\HookItemInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Resources\ModuleTrait;

final class HookHandlerFactoryTest extends TestCase
{
    use ModuleTrait;

    public function testCreateReturnsHandlerInstallerWithFactory()
    {
        $factory = function (array $item) {
            return $this->createMock(HookItemInterface::class);
        };

        $handler = HookHandlerFactory::create(
            $this->getModule(),
            [
                [
                    'name'              => 'displayHeader',
                ],
                [
                    'name'              => 'displayFooter',
                    'prestashopVersion' => '>=1.6.0.0',
                ],
            ],
            $factory
        );

        $this->assertInstanceOf(HookHandlerInterface::class, $handler);
    }

    public function testCreateReturnsHandlerWithoutFactory()
    {
        $handler = HookHandlerFactory::create(
            $this->getModule(),
            [
                [
                    'name'              => 'displayHeader',
                ],
                [
                    'name'              => 'displayFooter',
                    'prestashopVersion' => '>=1.6.0.0',
                ],
            ],
        );

        $this->assertInstanceOf(HookHandlerInterface::class, $handler);
    }
}
