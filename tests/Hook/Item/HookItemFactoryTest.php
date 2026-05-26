<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Hook\Item;

use PHPUnit\Framework\TestCase;
use RubenMartinDev\PrestaShopModuleInstaller\Hook\Item\HookItemFactory;
use RubenMartinDev\PrestaShopModuleInstaller\Hook\Item\HookItemInterface;

final class HookItemFactoryTest extends TestCase
{
    public function testCreateRetursHookItemWithRequireParameters()
    {
        $item = HookItemFactory::create('displayHeader');

        $this->assertInstanceOf(HookItemInterface::class, $item);
    }

    public function testCreateRetursHookItemWithOptionalParameters()
    {
        $item1 = HookItemFactory::create('displayHeader', null);
        $item2 = HookItemFactory::create('displayHeader', '>=1.6.0.0');
        $item3 = HookItemFactory::create('displayHeader', ['min' => '>=1.6.0.0']);
        $item4 = HookItemFactory::create('displayHeader', ['min' => '>=1.6.0.0', 'max' => '<=1.7.0.0']);

        $this->assertInstanceOf(HookItemInterface::class, $item1);
        $this->assertInstanceOf(HookItemInterface::class, $item2);
        $this->assertInstanceOf(HookItemInterface::class, $item3);
        $this->assertInstanceOf(HookItemInterface::class, $item4);
    }
}
