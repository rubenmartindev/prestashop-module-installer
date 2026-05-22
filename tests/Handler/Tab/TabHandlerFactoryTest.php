<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Handler\Tab;

use PHPUnit_Framework_MockObject_MockObject;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Item\TabItemInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\TabHandlerFactory;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\TabHandlerInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Handler\AbstractHandlerInstallerTestCase;

class TabHandlerFactoryTest extends AbstractHandlerInstallerTestCase
{
    public function testCreateReturnsHandlerInstallerWithFactory()
    {
        $handler = TabHandlerFactory::create(
            $this->module,
            [
                [
                    'className'         => 'AdminMyModule1',
                    'name'              => 'My tab 1',
                ],
                [
                    'className'         => 'AdminMyModule2',
                    'name'              => 'My tab 2',
                    'parentId'          => 1,
                    'position'          => 1,
                    'active'            => false,
                    'enabled'           => false,
                    'icon'              => 'extension',
                    'wording'           => 'My tab 2 name',
                    'wordingDomaing'    => 'Modules.MyModule.Navigation',
                ],
            ],
            $this->createFactoryMock()
        );

        $this->assertInstanceOf(TabHandlerInterface::class, $handler);

        $tabItem1 = $handler->getTab('AdminMyModule1');
        $tabItem2 = $handler->getTab('AdminMyModule2');

        $this->assertInstanceOf(TabItemInterface::class, $tabItem1);
        $this->assertInstanceOf(TabItemInterface::class, $tabItem2);
    }

    public function testCreateReturnsHandlerInstallerWithoutFactory()
    {
        $handler = TabHandlerFactory::create(
            $this->module,
            [
                [
                    'className'         => 'AdminMyModule1',
                    'name'              => 'My tab 1',
                ],
                [
                    'className'         => 'AdminMyModule2',
                    'name'              => 'My tab 2',
                    'parentId'          => 1,
                    'position'          => 1,
                    'active'            => false,
                    'enabled'           => false,
                    'icon'              => 'extension',
                    'wording'           => 'My tab 2 name',
                    'wordingDomaing'    => 'Modules.MyModule.Navigation',
                ],
            ]
        );

        $this->assertInstanceOf(TabHandlerInterface::class, $handler);

        $tabItem1 = $handler->getTab('AdminMyModule1');
        $tabItem2 = $handler->getTab('AdminMyModule2');

        $this->assertInstanceOf(TabItemInterface::class, $tabItem1);
        $this->assertInstanceOf(TabItemInterface::class, $tabItem2);
    }

    /**
     * @return callable
     */
    private function createFactoryMock()
    {
        return function (array $tab) {
            return $this->createTabItemMock($tab['className'], $tab['name']);
        };
    }

    /**
     * @param string $className
     * @param string $name
     *
     * @return TabItemInterface&PHPUnit_Framework_MockObject_MockObject
     */
    private function createTabItemMock($className, $name)
    {
        $tabItem = $this->createMock(TabItemInterface::class);

        $tabItem->method('getClassName')->willReturn($className);
        $tabItem->method('getName')->willReturn([
            1 => $name,
            2 => $name,
        ]);

        return $tabItem;
    }
}
