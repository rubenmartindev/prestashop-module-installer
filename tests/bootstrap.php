<?php

use RubenMartinDev\PrestaShopModuleInstaller\Tests\Stubs\Classes\Collection;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Stubs\Classes\Db\Db;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Stubs\Classes\Exception\PrestaShopDatabaseException;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Stubs\Classes\Exception\PrestaShopException;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Stubs\Classes\Language;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Stubs\Classes\Module\Module;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Stubs\Classes\ObjectModel;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Stubs\Classes\PrestaShopCollection;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Stubs\Classes\Tab;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Stubs\Classes\Validate;

// Composer
require_once __DIR__ . '/../vendor/autoload.php';

$stubs = [
    Collection::class                   => 'Collection',
    Db::class                           => 'Db',
    Language::class                     => 'Language',
    Module::class                       => 'Module',
    ObjectModel::class                  => 'ObjectModel',
    PrestaShopCollection::class         => 'PrestaShopCollection',
    PrestaShopDatabaseException::class  => 'PrestaShopDatabaseException',
    PrestaShopException::class          => 'PrestaShopException',
    Tab::class                          => 'Tab',
    Validate::class                     => 'Validate',
];

foreach ($stubs as $stubClassName => $alias) {
    if (!class_exists($alias)) {
        class_alias($stubClassName, $alias);
    }
}
