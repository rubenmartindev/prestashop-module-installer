<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Handler\Tab;

use PHPUnit_Framework_MockObject_MockObject;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Exception\TabHandlerException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Item\TabItemInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\TabHandlerFactory;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Handler\AbstractHandlerInstallerTestCase;

class TabHandlerFactoryTest extends AbstractHandlerInstallerTestCase
{
    public function testBuildThrowsExceptionWhenKeyClassNameIsMissing()
    {
        $this->expectException(TabHandlerException::class);
        $this->expectExceptionMessage('The key className is required');

        TabHandlerFactory::create(
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
        $this->expectException(TabHandlerException::class);
        $this->expectExceptionMessage('The key name is required');

        TabHandlerFactory::create(
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

        $handler = TabHandlerFactory::create(
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
