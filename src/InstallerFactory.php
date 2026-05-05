<?php

namespace RubenMartinDev\PrestaShopModuleInstaller;

use Module;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Database\DatabaseHandlerInstallerFactory;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Database\DatabaseHandlerInstallerInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\HookHandlerInstallerFactory;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\HookHandlerInstallerInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\TabHandlerInstaller;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\TabHandlerInstallerInterface;

/**
 * @phpstan-import-type TQuery from DatabaseHandlerInstallerFactory as TDatabaseQuery
 * @phpstan-import-type TQueries from DatabaseHandlerInstallerFactory as TDatabaseQueries
 *
 * @phpstan-import-type THook from HookHandlerInstallerFactory as THooksHook
 * @phpstan-import-type THooks from HookHandlerInstallerFactory as THooksHooks
 *
 * @phpstan-import-type TBuild from TabHandlerInstallerInterface as TTabsBuild
 */
class InstallerFactory
{
    /**
     * @param Module $module
     * @param array{
     *   database?: TDatabaseQueries,
     *   hooks?: THooksHooks,
     *   tabs?: TTabsBuild,
     * } $handlers
     * @param callable(Module $module, TDatabaseQuery $properties): DatabaseHandlerInstallerInterface|null $factoryDatabase
     * @param callable(Module $module, THooksHook $properties): HookHandlerInstallerInterface|null $factoryHooks
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
                return DatabaseHandlerInstallerFactory::create($module, $properties);
            }
        ;
        $factoryHooks = \is_callable($factoryHooks)
            ? $factoryHooks
            : function (Module $module, array $properties) {
                return HookHandlerInstallerFactory::create($module, $properties);
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
