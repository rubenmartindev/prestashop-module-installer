<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Configuration\Item;

use PHPUnit\Framework\TestCase;
use RubenMartinDev\PrestaShopModuleInstaller\Configuration\Item\ConfigurationItem;
use RubenMartinDev\PrestaShopModuleInstaller\Configuration\Item\ConfigurationItemInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Configuration\ValueObject\Name;
use RubenMartinDev\PrestaShopModuleInstaller\Configuration\ValueObject\Value;

final class ConfigurationItemTest extends TestCase
{
    /** @var Name */
    private $name;

    /** @var Value */
    private $value;

    protected function setUp()
    {
        parent::setUp();

        $this->name     = new Name('my_configuration', 'my_prefix');
        $this->value    = new Value('my value');
    }

    public function testConstructReturnsConfigurationItem()
    {
        $item = new ConfigurationItem(
            $this->name,
            $this->value
        );

        $this->assertInstanceOf(ConfigurationItemInterface::class, $item);
    }

    public function testCreateFromReturnConfigurationItemWithRequiredParameters()
    {
        $callback = function () {
            return 'my value';
        };

        $item1 = ConfigurationItem::createFrom('my_configuration', 'my value');
        $item2 = ConfigurationItem::createFrom('my_configuration', $callback);

        $this->assertInstanceOf(ConfigurationItemInterface::class, $item1);
        $this->assertInstanceOf(ConfigurationItemInterface::class, $item2);
    }

    public function testCreateFromReturnConfigurationItemWithOptionalParameters()
    {
        $item1 = ConfigurationItem::createFrom('my_configuration', 'my value', null);
        $item2 = ConfigurationItem::createFrom('my_configuration', 'my value', 'my_prefix');

        $this->assertInstanceOf(ConfigurationItemInterface::class, $item1);
        $this->assertInstanceOf(ConfigurationItemInterface::class, $item2);
    }

    public function testGetNameReturnsValueObject()
    {
        $item = new ConfigurationItem($this->name, $this->value);

        $this->assertEquals($this->name, $item->getName());
    }

    public function testGetValueReturnsValueObject()
    {
        $item = new ConfigurationItem($this->name, $this->value);

        $this->assertEquals($this->value, $item->getValue());
    }
}
