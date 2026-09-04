<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';

use RubenMartinDev\PrestaShopModuleInstaller\Installer;

class InstallerDemo extends Module
{
    public function __construct()
    {
        $this->name = 'installerdemo';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'RubenMartinDev';
        $this->need_instance = 0;
        $this->bootstrap = true;
        $this->ps_versions_compliancy = ['min' => '1.6.0.4', 'max' => _PS_VERSION_];

        parent::__construct();

        $this->displayName = 'Module Installer Demo';
        $this->description = 'Development fixture for PrestaShop Module Installer.';
    }

    public function install()
    {
        return parent::install() && $this->getInstaller()->install();
    }

    public function uninstall()
    {
        return $this->getInstaller()->uninstall() && parent::uninstall();
    }

    private function getInstaller()
    {
        return Installer::createFrom($this, [
            'configuration' => [
                [
                    'name' => 'enabled',
                    'value' => true,
                ],
            ],
            'database' => [
                [
                    'table_name' => 'installer_demo',
                    'query' => 'CREATE TABLE `{{DB_PREFIX}}installer_demo` (' . PHP_EOL
                        . ' `id_installer_demo` INT UNSIGNED NOT NULL AUTO_INCREMENT,' . PHP_EOL
                        . ' PRIMARY KEY (`id_installer_demo`)' . PHP_EOL
                        . ') ENGINE={{ENGINE_TYPE}} DEFAULT CHARSET=utf8;',
                ],
            ],
            'tabs' => [
                [
                    'class_name' => 'AdminInstallerDemo',
                    'name' => 'Module Installer Demo',
                ],
            ],
        ]);
    }
}
