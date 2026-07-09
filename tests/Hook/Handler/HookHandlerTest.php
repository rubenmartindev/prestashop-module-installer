<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Hook\Handler;

use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Exception\ItemTypeIsInvalidException;
use RubenMartinDev\PrestaShopModuleInstaller\Hook\Handler\Exception\FailedRegisterHookException;
use RubenMartinDev\PrestaShopModuleInstaller\Hook\Handler\HookHandler;
use RubenMartinDev\PrestaShopModuleInstaller\Hook\Handler\HookHandlerInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Hook\Item\HookItemInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Hook\ValueObject\Name;
use RubenMartinDev\PrestaShopModuleInstaller\Hook\ValueObject\PrestaShopVersion;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Resources\ModuleTrait;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Resources\Stubs\Classes\Module\ModuleStub;

final class HookHandlerTest extends TestCase
{
    use ModuleTrait;

    public function testConstructThrowsExceptionWhenItemsIsInvalid()
    {
        $this->expectException(ItemTypeIsInvalidException::class);

        new HookHandler(
            $this->getModule(),
            ['foobar']
        );
    }

    public function testConstructReturnsHandler()
    {
        $handler = new HookHandler(
            $this->getModule(),
            [$this->createItemMock('displayHeader')]
        );

        $this->assertInstanceOf(HookHandlerInterface::class, $handler);
    }

    public function testCreateFromReturnHandlerWithRequireParameters()
    {
        $handler = HookHandler::createFrom(
            $this->getModule(),
            [
                [
                    'name'              => 'displayHeader',
                ],
            ]
        );

        $this->assertInstanceOf(HookHandlerInterface::class, $handler);
    }

    public function testCreateFromReturnHandlerWithOptionalParameters()
    {
        $handler = HookHandler::createFrom(
            $this->getModule(),
            [
                [
                    'name'              => 'displayFooter',
                    'prestashopVersion' => '>=1.6.0.0',
                ],
            ]
        );

        $this->assertInstanceOf(HookHandlerInterface::class, $handler);
    }

    public function testGetItemsReturnsItems()
    {
        $item = $this->createItemMock('displayHeader');

        $handler = new HookHandler(
            $this->getModule(),
            [$item]
        );

        $this->assertSame([$item], $handler->getItems());
    }

    public function testAddItemAddsItem()
    {
        $item1 = $this->createItemMock('displayHeader');
        $item2 = $this->createItemMock('displayFooter');

        $handler = new HookHandler(
            $this->getModule(),
            [$item1]
        );

        $result = $handler->addItem($item2, 5);

        $this->assertSame([0 => $item1, 5 => $item2], $handler->getItems());
        $this->assertSame($result, $handler);
    }

    public function testRemoveItemRemovesItem()
    {
        $item1 = $this->createItemMock('displayHeader');
        $item2 = $this->createItemMock('displayFooter');

        $handler = new HookHandler(
            $this->getModule(),
            [$item1, $item2]
        );

        $result = $handler->removeItem(0);

        $this->assertSame([1 => $item2], $handler->getItems());
        $this->assertSame($result, $handler);
    }

    /**
     * @runInSeparateProcess
     */
    public function testInstallThrowsExceptionWhenRegisteringHookFails()
    {
        $this->expectException(FailedRegisterHookException::class);

        ModuleStub::$forceReturnFalseOnRegisterHook = true;

        $handler = new HookHandler(
            $this->getModule(),
            [$this->createItemMock('displayHeader')]
        );

        $handler->install();
    }

    /**
     * @runInSeparateProcess
     */
    public function testInstallReturnsTrue()
    {
        \define('_PS_VERSION_', 1.0);

        $handler = new HookHandler(
            $this->getModule(),
            [$this->createItemMock('displayHeader')]
        );

        $this->assertTrue($handler->install());
    }

    public function testUninstallReturnsTrue()
    {
        $handler = new HookHandler(
            $this->getModule(),
            [$this->createItemMock('displayHeader')]
        );

        $this->assertTrue($handler->uninstall());
    }

    /**
     * @param string $name
     * @param array $prestashopVersion
     *
     * @return HookItemInterface|PHPUnit_Framework_MockObject_MockObject
     */
    private function createItemMock($name, $prestashopVersion = ['min' => null, 'max' => null])
    {
        $item = $this->createMock(HookItemInterface::class);

        $item->method('getName')->willReturn(new Name($name));
        $item->method('getPrestaShopVersion')->willReturn(new PrestaShopVersion($prestashopVersion));

        return $item;
    }
}
