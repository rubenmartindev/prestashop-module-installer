<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Handler\Hook;

use PHPUnit_Framework_MockObject_MockObject;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\HandlerInstallerInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\HookHandlerFactory;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\Item\Exception\NameIsInvalidException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\Item\Exception\PrestaShopVersionIsInvalidException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\Item\HookItemInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Handler\AbstractHandlerInstallerTestCase;

class HookHandlerFactoryTest extends AbstractHandlerInstallerTestCase
{
    public function testCreateThrowsExceptionWhenKeyNameIsMissing()
    {
        $this->expectException(NameIsInvalidException::class);

        HookHandlerFactory::create(
            $this->module,
            [
                [],
            ]
        );
    }

    public function testCreateThrowsExceptionWhenKeyPrestashopVersionIsInvalid()
    {
        $this->expectException(PrestaShopVersionIsInvalidException::class);

        HookHandlerFactory::create(
            $this->module,
            [
                [
                    'name'              => 'displayHeader',
                    'prestashopVersion' => 1.0,
                ],
            ]
        );
    }

    public function testCreateReturnsHandlerInstallerWithoutFactory()
    {
        $handler = HookHandlerFactory::create(
            $this->module,
            [
                [
                    'name'              => 'displayHeader',
                ],
                [
                    'name'              => 'displayFooter',
                    'prestashopVersion' => '>=1.0',
                ],
            ]
        );

        $this->assertInstanceOf(HandlerInstallerInterface::class, $handler);

        $tabItem1 = $handler->getHook('displayHeader');
        $tabItem2 = $handler->getHook('displayFooter');

        $this->assertInstanceOf(HookItemInterface::class, $tabItem1);
        $this->assertInstanceOf(HookItemInterface::class, $tabItem2);
    }

    public function testCreateReturnsHandlerInstallerWithFactory()
    {
        $factory = function (array $hook) {
            return $this->createHookItemMock(
                $hook['name'],
                isset($hook['prestashopVersion']) ? $hook['prestashopVersion'] : null
            );
        };

        $handler = HookHandlerFactory::create(
            $this->module,
            [
                [
                    'name'              => 'displayHeader',
                ],
                [
                    'name'              => 'displayFooter',
                    'prestashopVersion' => '>=1.1',
                ],
            ],
            $factory
        );

        $this->assertInstanceOf(HandlerInstallerInterface::class, $handler);

        $hookItem1 = $handler->getHook('displayHeader');
        $hookItem2 = $handler->getHook('displayFooter');

        $this->assertInstanceOf(HookItemInterface::class, $hookItem1);
        $this->assertInstanceOf(HookItemInterface::class, $hookItem2);
    }

    /**
     * @param string $hookName
     * @param string|null $prestashopVersion
     *
     * @return HookItemInterface&PHPUnit_Framework_MockObject_MockObject
     */
    private function createHookItemMock($hookName, $prestashopVersion = null)
    {
        $hookItem = $this->createMock(HookItemInterface::class);

        $hookItem->method('getName')->willReturn($hookName);
        $hookItem->method('getPrestaShopVersion')->willReturn($prestashopVersion);

        return $hookItem;
    }
}
