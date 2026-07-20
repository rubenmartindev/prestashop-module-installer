<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Configuration\Item;

use PHPUnit\Framework\TestCase;
use RubenMartinDev\PrestaShopModuleInstaller\Configuration\Item\ConfigurationItem;
use RubenMartinDev\PrestaShopModuleInstaller\Configuration\Item\ConfigurationItemInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Configuration\ValueObject\Name;
use RubenMartinDev\PrestaShopModuleInstaller\Configuration\ValueObject\Value;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Resources\ModuleTrait;

final class ConfigurationItemTest extends TestCase
{
    use ModuleTrait;

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

        $item1 = ConfigurationItem::createFrom($this->getModule(), ['name' => 'my_configuration', 'value' => 'my value']);
        $item2 = ConfigurationItem::createFrom($this->getModule(), ['name' => 'my_configuration', 'value' => $callback]);

        $this->assertInstanceOf(ConfigurationItemInterface::class, $item1);
        $this->assertInstanceOf(ConfigurationItemInterface::class, $item2);
    }

    public function testCreateFromReturnConfigurationItemWithOptionalParameters()
    {
        $item1 = ConfigurationItem::createFrom($this->getModule(), ['name' => 'my_configuration', 'value' => 'my value', 'prefix' => null]);
        $item2 = ConfigurationItem::createFrom($this->getModule(), ['name' => 'my_configuration', 'value' => 'my value', 'prefix' => 'my_prefix']);

        $this->assertInstanceOf(ConfigurationItemInterface::class, $item1);
        $this->assertInstanceOf(ConfigurationItemInterface::class, $item2);
    }

    public function testGetTypeReturnsString()
    {
        $item = new ConfigurationItem($this->name, $this->value);

        $this->assertEquals('configuration', $item->getType());
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
