<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Tab\ValueObject;

use PHPUnit\Framework\TestCase;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject\Exception\RouteNameIsEmptyException;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject\Exception\RouteNameTypeIsInvalidException;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject\RouteName;
use RubenMartinDev\PrestaShopModuleInstaller\ValueObject\ValueObjectInterface;

final class RouteNameTest extends TestCase
{
    public function testConstructThrowsExceptionWhenTypeIsInvalid()
    {
        $this->expectException(RouteNameTypeIsInvalidException::class);

        new RouteName(1);
    }

    public function testConstructThrowsExceptionWhenStringIsEmpty()
    {
        $this->expectException(RouteNameIsEmptyException::class);

        new RouteName('');
    }

    public function testConstructReturnsValueObject()
    {
        $routeName1 = new RouteName(null);
        $routeName2 = new RouteName('admin_my_module_my_tab');

        $this->assertInstanceOf(ValueObjectInterface::class, $routeName1);
        $this->assertInstanceOf(ValueObjectInterface::class, $routeName2);
    }

    public function testIsEmptyReturnsFalse()
    {
        $routeName = new RouteName('admin_my_module_my_tab');

        $this->assertFalse($routeName->isEmpty());
    }

    public function testIsEmptyReturnsTrue()
    {
        $routeName = new RouteName(null);

        $this->assertTrue($routeName->isEmpty());
    }

    public function testGetValueReturnsString()
    {
        $routeName = new RouteName('admin_my_module_my_tab');

        $this->assertSame('admin_my_module_my_tab', $routeName->getValue());
    }

    public function testGetValueReturnsNull()
    {
        $routeName = new RouteName(null);

        $this->assertSame(null, $routeName->getValue());
    }
}
