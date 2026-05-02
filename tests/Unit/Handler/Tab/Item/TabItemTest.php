<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Unit\Handler\Tab\Item;

use PHPUnit\Framework\TestCase;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\Item\Exception\NameIsEmptyException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Item\Exception\ClassNameIsEmptyException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Item\Exception\ClassNameIsNotStringException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Item\Exception\NameIsNotStringOrArrayException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Item\Exception\NameMissingLanguageIsoCodeEnException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Item\Exception\ParentIdIsEmptyException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Item\Exception\ParentIdIsNotStringOrArrayException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Item\TabItem;

class TabItemTest extends TestCase
{
    public function testConstructThrowsExceptionWhenClassNameIsNotString()
    {
        $this->expectException(ClassNameIsNotStringException::class);

        new TabItem(123, 'My tab');
    }

    public function testConstructThrowsExceptionWhenClassNameIsEmpty()
    {
        $this->expectException(ClassNameIsEmptyException::class);

        new TabItem('', 'My tab');
    }

    public function testConstructThrowsExceptionWhenNameIsNotStringOrArray()
    {
        $this->expectException(NameIsNotStringOrArrayException::class);

        new TabItem('AdminMyModule', 123);
    }

    public function testConstructThrowsExceptionWhenNameAsStringEmpty()
    {
        $this->expectException(NameIsEmptyException::class);

        $item = new TabItem('AdminMyModule', '');
    }

    public function testConstructWithNameAsString()
    {
        $item = new TabItem('AdminMyModule', 'My tab');

        $this->assertSame('AdminMyModule', $item->getClassName());
        $this->assertCount(2, $item->getName());
        $this->assertSame('My tab', $item->getName()[1]);
        $this->assertSame('My tab', $item->getName()[2]);
        $this->assertSame(-1, $item->getParentId());
        $this->assertSame(0, $item->getPosition());
        $this->assertTrue($item->isActive());
    }

    public function testConstructThrowsExceptionWhenNameAsArrayEmpty()
    {
        $this->expectException(NameIsEmptyException::class);

        new TabItem('AdminMyModule', []);
    }

    public function testConstructWithNameAsArray()
    {
        $item = new TabItem('AdminMyModule', [
            'en' => 'My tab in English',
            'es' => 'My tab in Spanish',
        ]);

        $this->assertSame('AdminMyModule', $item->getClassName());
        $this->assertCount(2, $item->getName());
        $this->assertSame('My tab in English', $item->getName()[1]);
        $this->assertSame('My tab in Spanish', $item->getName()[2]);
        $this->assertSame(-1, $item->getParentId());
        $this->assertSame(0, $item->getPosition());
        $this->assertTrue($item->isActive());
    }

    public function testConstructWithNameAsArrayMissingLanguageIso()
    {
        $item = new TabItem('AdminMyModule', [
            'en' => 'My tab in English',
        ]);

        $this->assertSame('AdminMyModule', $item->getClassName());
        $this->assertCount(2, $item->getName());
        $this->assertSame('My tab in English', $item->getName()[1]);
        $this->assertSame('My tab in English', $item->getName()[2]);
        $this->assertSame(-1, $item->getParentId());
        $this->assertSame(0, $item->getPosition());
        $this->assertTrue($item->isActive());
    }

    public function testConstructWithNameAsArrayThrowsExceptionWhenMissingLanguageEn()
    {
        $this->expectException(NameMissingLanguageIsoCodeEnException::class);

        new TabItem('AdminMyModule', [
            'es' => 'My tab in Spanish',
        ]);
    }

    public function testConstructThrowExceptionWhenParentIdIsNotIntOrString()
    {
        $this->expectException(ParentIdIsNotStringOrArrayException::class);

        new TabItem('AdminMyModule', 'My tab', []);
    }

    public function testConstructThrowExceptionWhenParentIdAsStringIsEmpty()
    {
        $this->expectException(ParentIdIsEmptyException::class);

        new TabItem('AdminMyModule', 'My tab', '');
    }

    public function testConstructWithParentIdAsInt()
    {
        $item = new TabItem('AdminMyModule', 'My tab', 2);

        $this->assertSame('AdminMyModule', $item->getClassName());
        $this->assertCount(2, $item->getName());
        $this->assertSame('My tab', $item->getName()[1]);
        $this->assertSame('My tab', $item->getName()[2]);
        $this->assertSame(2, $item->getParentId());
        $this->assertSame(0, $item->getPosition());
        $this->assertTrue($item->isActive());
    }

    public function testConstructWithParentIdAsString()
    {
        $item = new TabItem('AdminMyModule', 'My tab', 'AdminParentTab');

        $this->assertSame('AdminMyModule', $item->getClassName());
        $this->assertCount(2, $item->getName());
        $this->assertSame('My tab', $item->getName()[1]);
        $this->assertSame('My tab', $item->getName()[2]);
        $this->assertSame(1, $item->getParentId());
        $this->assertSame(0, $item->getPosition());
        $this->assertTrue($item->isActive());
    }

    public function testConstructWithPositionAndActive()
    {
        $item = new TabItem('AdminMyModule', 'My tab', 1, 5, false);

        $this->assertSame('AdminMyModule', $item->getClassName());
        $this->assertCount(2, $item->getName());
        $this->assertSame('My tab', $item->getName()[1]);
        $this->assertSame('My tab', $item->getName()[2]);
        $this->assertSame(1, $item->getParentId());
        $this->assertSame(5, $item->getPosition());
        $this->assertFalse($item->isActive());
    }

    public function testGetters()
    {
        $item = new TabItem('AdminMyModule', 'My tab', 1, 5, false);

        $this->assertSame('AdminMyModule', $item->getClassName());
        $this->assertCount(2, $item->getName());
        $this->assertSame('My tab', $item->getName()[1]);
        $this->assertSame('My tab', $item->getName()[2]);
        $this->assertSame(1, $item->getParentId());
        $this->assertSame(5, $item->getPosition());
        $this->assertFalse($item->isActive());
    }
}
