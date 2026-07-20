<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Configuration\Handler;

use PHPUnit\Framework\TestCase;
use RubenMartinDev\PrestaShopModuleInstaller\Configuration\Handler\ConfigurationHandler;
use RubenMartinDev\PrestaShopModuleInstaller\Configuration\Handler\ConfigurationHandlerInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Configuration\Handler\Exception\FailedAddConfigurationException;
use RubenMartinDev\PrestaShopModuleInstaller\Configuration\Handler\Exception\FailedDeleteConfigurationException;
use RubenMartinDev\PrestaShopModuleInstaller\Configuration\Item\ConfigurationItem;
use RubenMartinDev\PrestaShopModuleInstaller\Configuration\Item\ConfigurationItemInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Configuration\ValueObject\Name;
use RubenMartinDev\PrestaShopModuleInstaller\Configuration\ValueObject\Value;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Exception\ItemTypeIsInvalidException;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Resources\ModuleTrait;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Resources\Stubs\Classes\ConfigurationStub;

final class ConfigurationHandlerTest extends TestCase
{
    use ModuleTrait;

    public function testConstructThrowsExceptionWhenItemsIsInvalid()
    {
        $this->expectException(ItemTypeIsInvalidException::class);

        new ConfigurationHandler(
            $this->getModule(),
            ['foobar']
        );
    }

    public function testConstructReturnsHandler()
    {
        $handler = new ConfigurationHandler(
            $this->getModule(),
            [$this->createItemMock('my_configuration', 'my value')]
        );

        $this->assertInstanceOf(ConfigurationHandlerInterface::class, $handler);
    }

    public function testCreateFromReturnHandlerWithRequireParameters()
    {
        $handler = ConfigurationHandler::createFrom(
            $this->getModule(),
            [
                [
                    'name'  => 'my_configuration',
                ],
            ]
        );

        $this->assertInstanceOf(ConfigurationHandlerInterface::class, $handler);
    }

    public function testCreateFromReturnHandlerWithOptionalParameters()
    {
        $handler = ConfigurationHandler::createFrom(
            $this->getModule(),
            [
                [
                    'name'      => 'my_configuration',
                    'value'     => 'my value',
                    'prefix'    => 'my_prefix',
                ],
            ]
        );

        $this->assertInstanceOf(ConfigurationHandlerInterface::class, $handler);
    }

    public function testGetItemsReturnsItems()
    {
        $item = $this->createItemMock('my_configuration', 'my value');

        $handler = new ConfigurationHandler(
            $this->getModule(),
            [$item]
        );

        $this->assertSame([$item], $handler->getItems());
    }

    public function testAddItemAddsItem()
    {
        $item1 = $this->createItemMock('my_configuration', 'my value');
        $item2 = $this->createItemMock('my_other_configuration', 'my other value');

        $handler = new ConfigurationHandler(
            $this->getModule(),
            [$item1]
        );

        $result = $handler->addItem($item2, 5);

        $this->assertSame([0 => $item1, 5 => $item2], $handler->getItems());
        $this->assertSame($result, $handler);
    }

    public function testRemoveItemRemovesItem()
    {
        $item1 = $this->createItemMock('my_configuration', 'my value');
        $item2 = $this->createItemMock('my_other_configuration', 'my other value');

        $handler = new ConfigurationHandler(
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
    public function testInstallThrowsExceptionWhenFailedAdd()
    {
        ConfigurationStub::$forceReturnFalseOnUpdateValue = true;

        $this->expectException(FailedAddConfigurationException::class);

        $handler = new ConfigurationHandler(
            $this->getModule(),
            [$this->createItemMock('my_configuration', 'my value')]
        );

        $handler->install();
    }

    public function testInstallReturnsTrue()
    {
        $handler = new ConfigurationHandler(
            $this->getModule(),
            [$this->createItemMock('my_configuration', 'my value')]
        );

        $this->assertTrue($handler->install());
    }

    /**
     * @runInSeparateProcess
     */
    public function testUninstallThrowsExceptionWhenFailedAdd()
    {
        ConfigurationStub::$forceReturnFalseOnDeleteByName = true;

        $this->expectException(FailedDeleteConfigurationException::class);

        $handler = new ConfigurationHandler(
            $this->getModule(),
            [$this->createItemMock('my_configuration', 'my value')]
        );

        $handler->uninstall();
    }

    public function testUninstallReturnsTrue()
    {
        $handler = new ConfigurationHandler(
            $this->getModule(),
            [$this->createItemMock('my_configuration', 'my value')]
        );

        $this->assertTrue($handler->uninstall());
    }

    /**
     * @param string $name
     * @param mixed $value
     *
     * @return ConfigurationItemInterface
     */
    private function createItemMock($name, $value)
    {
        return new ConfigurationItem(
            new Name($name),
            new Value($value)
        );
    }
}
