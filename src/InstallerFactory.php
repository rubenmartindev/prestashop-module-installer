<?php

namespace RubenMartinDev\PrestaShopModuleInstaller;

use Module;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Database\DatabaseHandlerFactory;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Database\DatabaseHandlerInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\HookHandlerFactory;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\HookHandlerInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\TabHandler;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\TabHandlerFactory;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\TabHandlerInterface;

/**
 * @phpstan-import-type TQuery from DatabaseHandlerFactory as TDatabaseQuery
 * @phpstan-import-type TQueries from DatabaseHandlerFactory as TDatabaseQueries
 *
 * @phpstan-import-type THook from HookHandlerFactory as THooksHook
 * @phpstan-import-type THooks from HookHandlerFactory as THooksHooks
 *
 * @phpstan-import-type TTab from TabHandlerFactory as TTabsTab
 * @phpstan-import-type TTabs from TabHandlerFactory as TTabsTabs
 */
class InstallerFactory
{
    /**
     * @param Module $module
     * @param array{
     *   database?: TDatabaseQueries,
     *   hooks?: THooksHooks,
     *   tabs?: TTabsTabs,
     * } $handlers
     * @param callable(Module $module, TDatabaseQuery $properties): DatabaseHandlerInterface|null $factoryDatabase
     * @param callable(Module $module, THooksHook $properties): HookHandlerInterface|null $factoryHooks
     * @param callable(Module $module, TTabsTab $properties): TabHandlerInterface|null $factoryTabs
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
                return DatabaseHandlerFactory::create($module, $properties);
            }
        ;
        $factoryHooks = \is_callable($factoryHooks)
            ? $factoryHooks
            : function (Module $module, array $properties) {
                return HookHandlerFactory::create($module, $properties);
            }
        ;
        $factoryTabs = \is_callable($factoryTabs)
            ? $factoryTabs
            : function (Module $module, array $properties) {
                return TabHandlerFactory::create($module, $properties);
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
