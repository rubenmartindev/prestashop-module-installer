<?php

use RubenMartinDev\PrestaShopModuleInstaller\Tests\Stubs\Classes\CollectionStub;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Stubs\Classes\ConfigurationStub;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Stubs\Classes\Db\DbStub;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Stubs\Classes\Exception\PrestaShopDatabaseExceptionStub;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Stubs\Classes\Exception\PrestaShopExceptionStub;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Stubs\Classes\LanguageStub;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Stubs\Classes\Module\ModuleStub;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Stubs\Classes\ObjectModelStub;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Stubs\Classes\PrestaShopCollectionStub;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Stubs\Classes\TabStub;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Stubs\Classes\ValidateStub;

// Composer
require_once __DIR__ . '/../vendor/autoload.php';

$stubs = [
    PrestaShopExceptionStub::class          => 'PrestaShopException',
    ObjectModelStub::class                  => 'ObjectModel',
    CollectionStub::class                   => 'Collection',

    ConfigurationStub::class                => 'Configuration',
    DbStub::class                           => 'Db',
    LanguageStub::class                     => 'Language',
    ModuleStub::class                       => 'Module',
    PrestaShopCollectionStub::class         => 'PrestaShopCollection',
    PrestaShopDatabaseExceptionStub::class  => 'PrestaShopDatabaseException',
    TabStub::class                          => 'Tab',
    ValidateStub::class                     => 'Validate',
];

foreach ($stubs as $stubClassName => $alias) {
    class_alias($stubClassName, $alias);
}
