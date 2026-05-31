<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Configuration\ValueObject;

use PHPUnit\Framework\TestCase;
use RubenMartinDev\PrestaShopModuleInstaller\Configuration\ValueObject\Value;
use RubenMartinDev\PrestaShopModuleInstaller\ValueObject\ValueObjectInterface;

final class ValueTest extends TestCase
{
    public function testConstructReturnsValueObject()
    {
        $name = new Value('my value');

        $this->assertInstanceOf(ValueObjectInterface::class, $name);
    }

    public function testGetValueReturnsStringWhenValueIsBoolean()
    {
        $name1 = new Value(true);
        $name2 = new Value(false);

        $this->assertSame('1', $name1->getValue());
        $this->assertSame('0', $name2->getValue());
    }

    public function testGetValueReturnsStringWhenValueIsString()
    {
        $name1 = new Value('my value');
        $name2 = new Value('  my value  ');

        $this->assertSame('my value', $name1->getValue());
        $this->assertSame('my value', $name2->getValue());
    }

    public function testGetValueReturnsStringWhenValueIsArray()
    {
        $value = ['my value 1', 'my value 2'];

        $name = new Value($value);

        $this->assertSame(\json_encode($value), $name->getValue());
    }

    public function testGetValueReturnsStringWhenValueIsObject()
    {
        $value = new \stdClass();
        $value->myProperty1 = 'my value 1';
        $value->myProperty2 = 'my value 2';

        $name = new Value($value);

        $this->assertSame(\serialize($value), $name->getValue());
    }
}
