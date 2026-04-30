<?php

use RubenMartinDev\PrestaShopModuleInstaller\Tests\Stubs\Classes\Exception\PrestaShopException;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Stubs\Classes\Module\Module;

// Composer
require_once __DIR__ . '/../vendor/autoload.php';

$stubs = [
    Module::class               => 'Module',
    PrestaShopException::class  => 'PrestaShopException',
];

foreach ($stubs as $stubClassName => $alias) {
    if (!class_exists($alias)) {
        class_alias($stubClassName, $alias);
    }
}
