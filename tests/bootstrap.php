<?php

use RubenMartinDev\PrestaShopModuleInstaller\Tests\Stubs\Classes\Collection;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Stubs\Classes\Exception\PrestaShopException;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Stubs\Classes\Language;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Stubs\Classes\Module\Module;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Stubs\Classes\ObjectModel;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Stubs\Classes\PrestaShopCollection;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Stubs\Classes\Tab;

// Composer
require_once __DIR__ . '/../vendor/autoload.php';

$stubs = [
    Collection::class           => 'Collection',
    Language::class             => 'Language',
    Module::class               => 'Module',
    ObjectModel::class          => 'ObjectModel',
    PrestaShopCollection::class => 'PrestaShopCollection',
    PrestaShopException::class  => 'PrestaShopException',
    Tab::class                  => 'Tab',
];

foreach ($stubs as $stubClassName => $alias) {
    if (!class_exists($alias)) {
        class_alias($stubClassName, $alias);
    }
}
