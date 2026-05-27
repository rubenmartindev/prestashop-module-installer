<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Tab\ValueObject;

use PHPUnit\Framework\TestCase;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject\ClassName;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject\Exception\ClassNameIsEmptyException;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject\Exception\ClassNameTypeIsInvalidException;

final class ClassNameTest extends TestCase
{
    public function testConstructThrowsExceptionWhenIsNotString()
    {
        $this->expectException(ClassNameTypeIsInvalidException::class);

        new ClassName(1);
    }

    public function testConstructThrowsExceptionWhenStringisEmpty()
    {
        $this->expectException(ClassNameIsEmptyException::class);

        new ClassName('');
    }

    public function testGetValueReturnsString()
    {
        $className = new ClassName('AdminMyModule');

        $this->assertSame('AdminMyModule', $className->getValue());
    }
}
