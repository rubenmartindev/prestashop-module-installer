<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Handler\Hook;

use PHPUnit_Framework_MockObject_MockObject;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\Exception\HookHandlerInstallerException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\HookHandlerInstallerFactory;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\Item\HookItemInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Handler\AbstractHandlerInstallerTestCase;

class HookHandlerInstallerFactoryTest extends AbstractHandlerInstallerTestCase
{
    public function testCreateThrowsExceptionWhenKeyNameIsMissing()
    {
        $this->expectException(HookHandlerInstallerException::class);
        $this->expectExceptionMessage('The key name is required');

        HookHandlerInstallerFactory::create(
            $this->module,
            [
                [],
            ]
        );
    }

    public function testCreate()
    {
        $factory = function (array $hook) {
            return $this->createHookItemMock($hook['name']);
        };

        $handler = HookHandlerInstallerFactory::create(
            $this->module,
            [
                [
                    'name' => 'displayHeader',
                ],
                [
                    'name' => 'displayFooter',
                ],
            ],
            $factory
        );

        $hookItem1 = $handler->getHook('displayHeader');
        $hookItem2 = $handler->getHook('displayFooter');

        $this->assertInstanceOf(HookItemInterface::class, $hookItem1);
        $this->assertInstanceOf(HookItemInterface::class, $hookItem2);
    }

    /**
     * @param string $hookName
     *
     * @return HookItemInterface|PHPUnit_Framework_MockObject_MockObject
     */
    private function createHookItemMock($hookName)
    {
        $hookItem = $this->createMock(HookItemInterface::class);

        $hookItem->method('getName')->willReturn($hookName);

        return $hookItem;
    }
}
