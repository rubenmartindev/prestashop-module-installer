<?php

namespace RubenMartinDev\PrestaShopModuleInstaller;

use Module;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Database\DatabaseHandlerFactory;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Database\DatabaseHandlerInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\HookHandlerFactory;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\HookHandlerInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\TabHandlerFactory;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\TabHandlerInterface;

/**
 * @phpstan-import-type TQueries from DatabaseHandlerFactory as TDatabase
 * @phpstan-import-type THooks from HookHandlerFactory as THooks
 * @phpstan-import-type TTabs from TabHandlerFactory as TTabs
 */
class InstallerFactory
{
    /**
     * @param Module $module
     * @param array{
     *   database?: TDatabase,
     *   hooks?: THooks,
     *   tabs?: TTabs,
     * } $handlers
     * @param callable(Module $module, TDatabase $queries): DatabaseHandlerInterface|null $factoryDatabase
     * @param callable(Module $module, THooks $hooks): HookHandlerInterface|null $factoryHooks
     * @param callable(Module $module, TTabs $tabs): TabHandlerInterface|null $factoryTabs
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
        $factoryDatabase    = \is_callable($factoryDatabase)    ? $factoryDatabase  : [self::class, 'defaultDatabaseFactory'];
        $factoryHooks       = \is_callable($factoryHooks)       ? $factoryHooks     : [self::class, 'defaultHooksFactory'];
        $factoryTabs        = \is_callable($factoryTabs)        ? $factoryTabs      : [self::class, 'defaultTabsFactory'];

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

    /**
     * @param Module $module
     * @param TDatabase $queries
     *
     * @return DatabaseHandlerInterface
     */
    private static function defaultDatabaseFactory(Module $module, array $queries)
    {
        return DatabaseHandlerFactory::create($module, $queries);
    }

    /**
     * @param Module $module
     * @param THooks $hooks
     *
     * @return HookHandlerInterface
     */
    private static function defaultHooksFactory(Module $module, array $hooks)
    {
        return HookHandlerFactory::create($module, $hooks);
    }

    /**
     * @param Module $module
     * @param TTabs $tabs
     *
     * @return TabHandlerInterface
     */
    private static function defaultTabsFactory(Module $module, array $tabs)
    {
        return TabHandlerFactory::create($module, $tabs);
    }
}
