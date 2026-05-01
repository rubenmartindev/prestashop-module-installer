<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Unit\Handler\Hook\Item;

use PHPUnit\Framework\TestCase;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\Item\Exception\NameIsInvalidException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\Item\HookItem;

class HookItemTests extends TestCase
{
    public function testCreate()
    {
        $hookItem = HookItem::create('displayHeader');

        $this->assertInstanceOf(HookItem::class, $hookItem);
    }

    public function testCreateThrowsExceptionWhenInvalidHookName()
    {
        $this->expectException(NameIsInvalidException::class);

        HookItem::create('invalid hook name');
    }

    public function testGetName()
    {
        $hookItem = HookItem::create('displayHeader');

        $this->assertEquals('displayHeader', $hookItem->getName());
    }
}
