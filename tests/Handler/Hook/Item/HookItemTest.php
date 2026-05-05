<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Handler\Hook\Item;

use PHPUnit\Framework\TestCase;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\Item\Exception\NameIsInvalidException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\Item\HookItem;

class HookItemTest extends TestCase
{
    public function testConstruct()
    {
        $item = new HookItem('displayHeader');

        $this->assertSame('displayHeader', $item->getName());
    }

    public function testConstructThrowsExceptionWhenNameIsEmpty()
    {
        $this->expectException(NameIsInvalidException::class);

        new HookItem('');
    }

    public function testConstructThrowsExceptionWhenNameIsInvalid()
    {
        $this->expectException(NameIsInvalidException::class);

        new HookItem('invalid name');
    }

    public function testGetName()
    {
        $hookItem = new HookItem('displayHeader');

        $this->assertEquals('displayHeader', $hookItem->getName());
    }
}
