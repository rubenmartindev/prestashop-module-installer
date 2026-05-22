<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Handler\Tab\Item;

use PHPUnit\Framework\TestCase;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Item\Exception\ClassNameIsEmptyException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Item\Exception\ClassNameTypeIsInvalidException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Item\Exception\NameIsEmptyException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Item\Exception\NameMissingLanguageIsoCodeEnException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Item\Exception\NameTypeIsInvalidException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Item\Exception\ParentIdIsEmptyException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Item\Exception\ParentIdTypeIsInvalidException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Item\TabItem;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Item\TabItemInterface;

class TabItemTest extends TestCase
{
    public function testConstructThrowsExceptionWhenClassNameIsTypeNotValid()
    {
        $this->expectException(ClassNameTypeIsInvalidException::class);

        new TabItem(123, 'My tab');
    }

    public function testConstructThrowsExceptionWhenClassNameIsEmpty()
    {
        $this->expectException(ClassNameIsEmptyException::class);

        new TabItem('', 'My tab');
    }

    public function testConstructThrowsExceptionWhenNameIsTypeNotValid()
    {
        $this->expectException(NameTypeIsInvalidException::class);

        new TabItem('AdminMyModule', 123);
    }

    public function testConstructThrowsExceptionWhenNameAsStringEmpty()
    {
        $this->expectException(NameIsEmptyException::class);

        new TabItem('AdminMyModule', '');
    }

    public function testConstructWithNameAsString()
    {
        $item = new TabItem('AdminMyModule', 'My tab');

        $this->assertInstanceOf(TabItemInterface::class, $item);
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

        $this->assertInstanceOf(TabItemInterface::class, $item);
    }

    public function testConstructWithNameAsArrayMissingLanguageIso()
    {
        $item = new TabItem('AdminMyModule', [
            'en' => 'My tab in English',
        ]);

        $this->assertInstanceOf(TabItemInterface::class, $item);
    }

    public function testConstructWithNameAsArrayThrowsExceptionWhenMissingLanguageEn()
    {
        $this->expectException(NameMissingLanguageIsoCodeEnException::class);

        new TabItem('AdminMyModule', [
            'es' => 'My tab in Spanish',
        ]);
    }

    public function testConstructThrowExceptionWhenParentIdIsTypeNotValid()
    {
        $this->expectException(ParentIdTypeIsInvalidException::class);

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

        $this->assertInstanceOf(TabItemInterface::class, $item);
    }

    public function testConstructWithParentIdAsString()
    {
        $item = new TabItem('AdminMyModule', 'My tab', 'AdminParentTab');

        $this->assertInstanceOf(TabItemInterface::class, $item);
    }

    public function testConstructWithRequireParameters()
    {
        $item = new TabItem('AdminMyModule', 'My tab');

        $this->assertInstanceOf(TabItemInterface::class, $item);
    }

    public function testConstructWithOptionalParameters()
    {
        $item = new TabItem('AdminMyModule', 'My tab', 1, 5, false, false, 'icon', 'My tab', 'Modules.MyModule.Navigation');

        $this->assertInstanceOf(TabItemInterface::class, $item);
    }

    public function testGettersWithRequireParameters()
    {
        $item = new TabItem('AdminMyModule', 'My tab');

        $this->assertSame('AdminMyModule', $item->getClassName());
        $this->assertCount(2, $item->getName());
        $this->assertSame('My tab', $item->getName()[1]);
        $this->assertSame('My tab', $item->getName()[2]);
        $this->assertSame(-1, $item->getParentId());
        $this->assertSame(0, $item->getPosition());
        $this->assertTrue($item->isActive());
        $this->assertTrue($item->isEnabled());
        $this->assertNull($item->getIcon());
        $this->assertSame('My tab', $item->getWording());
        $this->assertSame('Admin.Navigation.Menu', $item->getWordingDomain());
    }

    public function testGettersWithOptionalParameters()
    {
        $item = new TabItem('AdminMyModule', 'My tab', 1, 5, false, false, 'extension', 'My tab name', 'Modules.MyModule.Navigation');

        $this->assertSame('AdminMyModule', $item->getClassName());
        $this->assertCount(2, $item->getName());
        $this->assertSame('My tab', $item->getName()[1]);
        $this->assertSame('My tab', $item->getName()[2]);
        $this->assertSame(1, $item->getParentId());
        $this->assertSame(5, $item->getPosition());
        $this->assertFalse($item->isActive());
        $this->assertFalse($item->isEnabled());
        $this->assertSame('extension', $item->getIcon());
        $this->assertSame('My tab name', $item->getWording());
        $this->assertSame('Modules.MyModule.Navigation', $item->getWordingDomain());
    }
}
