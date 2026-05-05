<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Handler\Tab;

use PHPUnit_Framework_MockObject_MockObject;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Exception\TabHandlerInstallerException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Item\TabItemInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\TabHandlerInstallerFactory;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Handler\AbstractHandlerInstallerTestCase;

class TabHandlerInstallerFactoryTest extends AbstractHandlerInstallerTestCase
{
    public function testBuildThrowsExceptionWhenKeyClassNameIsMissing()
    {
        $this->expectException(TabHandlerInstallerException::class);
        $this->expectExceptionMessage('The key className is required');

        TabHandlerInstallerFactory::create(
            $this->module,
            [
                [
                    'name' => 'My tab',
                ],
            ]
        );
    }

    public function testBuildThrowsExceptionWhenKeyNameIsMissing()
    {
        $this->expectException(TabHandlerInstallerException::class);
        $this->expectExceptionMessage('The key name is required');

        TabHandlerInstallerFactory::create(
            $this->module,
            [
                [
                    'className' => 'AdminMyModule',
                ],
            ]
        );
    }

    public function testBuild()
    {
        $factory = function (array $tab) {
            return $this->createTabItemMock($tab['className'], $tab['name']);
        };

        $handler = TabHandlerInstallerFactory::create(
            $this->module,
            [
                [
                    'className' => 'AdminMyModule1',
                    'name'      => 'My tab 1',
                ],
                [
                    'className' => 'AdminMyModule2',
                    'name'      => 'My tab 2',
                ],
            ],
            $factory
        );

        $tabItem1 = $handler->getTab('AdminMyModule1');
        $tabItem2 = $handler->getTab('AdminMyModule2');

        $this->assertInstanceOf(TabItemInterface::class, $tabItem1);
        $this->assertInstanceOf(TabItemInterface::class, $tabItem2);
    }

    /**
     * @param string $className
     * @param string $name
     *
     * @return TabItemInterface|PHPUnit_Framework_MockObject_MockObject
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
