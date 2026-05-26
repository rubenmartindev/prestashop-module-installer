<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Handler;

use PHPUnit\Framework\TestCase;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\AbstractHandler;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Exception\ItemsIsEmptyException;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Resources\ModuleTrait;

final class AbstractHandlerTest extends TestCase
{
    use ModuleTrait;

    public function testConstructThrowsExceptionWhenItemsIsEmpty()
    {
        $this->expectException(ItemsIsEmptyException::class);

        $this->getMockForAbstractClass(AbstractHandler::class, [$this->getModule(), []]);
    }
}
