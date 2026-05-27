<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Tab\Handler;

use PHPUnit\Framework\TestCase;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\Handler\TabHandlerFactory;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\Handler\TabHandlerInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\Item\TabItemInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Resources\ModuleTrait;

final class TabHandlerFactoryTest extends TestCase
{
    use ModuleTrait;

    public function testCreateReturnsHandlerWithFactory()
    {
        $factory = function (array $item) {
            return $this->createMock(TabItemInterface::class);
        };

        $handler = TabHandlerFactory::create(
            $this->getModule(),
            [
                [
                    'className'     => 'AdminMyTab1',
                    'name'          => 'My tab 1',
                ],
                [
                    'className'     => 'AdminMyTab2',
                    'name'          => [1 => 'My tab2 1', 2 => 'My tab2 2'],
                    'parentId'      => 1,
                    'position'      => 5,
                    'isActive'      => false,
                    'isEnabled'     => false,
                    'routeName'     => 'admin_my_module_my_tab',
                    'icon'          => 'extension',
                    'wording'       => 'My tag',
                    'wordingDomain' => 'Modules.MyModule.Navigation',
                ],
            ],
            $factory
        );

        $this->assertInstanceOf(TabHandlerInterface::class, $handler);
    }

    public function testCreateReturnsTabHandlerWithoutFactory()
    {
        $handler = TabHandlerFactory::create(
            $this->getModule(),
            [
                [
                    'className'     => 'AdminMyTab1',
                    'name'          => 'My tab 1',
                ],
                [
                    'className'     => 'AdminMyTab2',
                    'name'          => [1 => 'My tab2 1', 2 => 'My tab2 2'],
                    'parentId'      => 1,
                    'position'      => 5,
                    'isActive'      => false,
                    'isEnabled'     => false,
                    'routeName'     => 'admin_my_module_my_tab',
                    'icon'          => 'extension',
                    'wording'       => 'My tag',
                    'wordingDomain' => 'Modules.MyModule.Navigation',
                ],
            ]
        );

        $this->assertInstanceOf(TabHandlerInterface::class, $handler);
    }

    public function testCreateReturnsDatabaseHandlerWithArrayWithoutAnOrder()
    {
        $handler = TabHandlerFactory::create(
            $this->getModule(),
            [
                [
                    'wording'       => 'My tag',
                    'wordingDomain' => 'Modules.MyModule.Navigation',
                    'icon'          => 'extension',
                    'isActive'      => false,
                    'isEnabled'     => false,
                    'className'     => 'AdminMyTab2',
                    'name'          => [1 => 'My tab2 1', 2 => 'My tab2 2'],
                    'parentId'      => 1,
                    'position'      => 5,
                    'routeName'     => 'admin_my_module_my_tab',
                ],
            ]
        );

        $this->assertInstanceOf(TabHandlerInterface::class, $handler);
    }
}
