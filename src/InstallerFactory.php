<?php

namespace RubenMartinDev\PrestaShopModuleInstaller;

use Module;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Database\DatabaseHandlerInstaller;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Database\DatabaseHandlerInstallerInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\HookHandlerInstaller;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\HookHandlerInstallerInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\TabHandlerInstaller;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\TabHandlerInstallerInterface;

/**
 * @phpstan-import-type TBuild from DatabaseHandlerInstallerInterface as TDatabaseBuild
 * @phpstan-import-type TBuild from HookHandlerInstallerInterface as THooksBuild
 * @phpstan-import-type TBuild from TabHandlerInstallerInterface as TTabsBuild
 */
class InstallerFactory
{
    /**
     * @param Module $module
     * @param array{
     *   database?: TDatabaseBuild,
     *   hooks?: THooksBuild,
     *   tabs?: TTabsBuild,
     * } $handlers
     * @param callable(Module $module, array $properties): DatabaseHandlerInstallerInterface|null $factoryDatabase
     * @param callable(Module $module, array $properties): HookHandlerInstallerInterface|null $factoryHooks
     * @param callable(Module $module, array $properties): TabHandlerInstallerInterface|null $factoryTabs
     *
     * @return InstallerInterface
     */
    public static function create(
        Module $module,
        array $handlers,
        $factoryDatabase = null,
        $factoryHooks = null,
        $factoryTabs = null
    ) {
        $factoryDatabase = \is_callable($factoryDatabase)
            ? $factoryDatabase
            : function (Module $module, array $properties) {
                return DatabaseHandlerInstaller::build($module, $properties);
            }
        ;
        $factoryHooks = \is_callable($factoryHooks)
            ? $factoryHooks
            : function (Module $module, array $properties) {
                return HookHandlerInstaller::build($module, $properties);
            }
        ;
        $factoryTabs = \is_callable($factoryTabs)
            ? $factoryTabs
            : function (Module $module, array $properties) {
                return TabHandlerInstaller::build($module, $properties);
            }
        ;

        foreach ($handlers as $name => &$properties) {
            if ('database' === $name) {
                $properties = $factoryDatabase($module, $properties);
            }

            if ('hooks' === $name) {
                $properties = $factoryHooks($module, $properties);
            }

            if ('tabs' === $name) {
                $properties = $factoryTabs($module, $properties);
            }
        }

        $handlers = \array_values($handlers);

        return new Installer($handlers);
    }
}
