<?php

use RubenMartinDev\PrestaShopModuleInstaller\Tests\Resources\Stubs\Classes\CollectionStub;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Resources\Stubs\Classes\ConfigurationStub;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Resources\Stubs\Classes\Db\DbStub;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Resources\Stubs\Classes\Exception\PrestaShopDatabaseExceptionStub;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Resources\Stubs\Classes\Exception\PrestaShopExceptionStub;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Resources\Stubs\Classes\LanguageStub;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Resources\Stubs\Classes\Module\ModuleStub;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Resources\Stubs\Classes\ObjectModelStub;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Resources\Stubs\Classes\PrestaShopCollectionStub;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Resources\Stubs\Classes\TabStub;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Resources\Stubs\Classes\ValidateStub;

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
