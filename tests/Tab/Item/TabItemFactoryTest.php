<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Tab\Item;

use PHPUnit\Framework\TestCase;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\Item\TabItemFactory;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\Item\TabItemInterface;

final class TabItemFactoryTest extends TestCase
{
    public function testCreateRetursTabItemWithRequireParameters()
    {
        $item = TabItemFactory::create(
            'AdminMyTab',
            'My tab'
        );

        $this->assertInstanceOf(TabItemInterface::class, $item);
    }

    public function testCreateRetursTabItemWithOptionalParameters()
    {
        $item = TabItemFactory::create(
            'AdminMyTab2',
            [1 => 'My tab2 1', 2 => 'My tab2 2'],
            1,
            5,
            false,
            false,
            'admin_my_module_my_tab',
            'extension',
            'My tag',
            'Modules.MyModule.Navigation'
        );

        $this->assertInstanceOf(TabItemInterface::class, $item);
    }
}
