<?php

namespace RubenMartinDev\PrestaShopModuleInstaller;

use Module;
use RubenMartinDev\PrestaShopModuleInstaller\Database\Handler\DatabaseHandlerFactory;
use RubenMartinDev\PrestaShopModuleInstaller\Database\Handler\DatabaseHandlerInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Hook\Handler\HookHandlerFactory;
use RubenMartinDev\PrestaShopModuleInstaller\Hook\Handler\HookHandlerInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\Handler\TabHandlerFactory;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\Handler\TabHandlerInterface;

/**
 * @phpstan-import-type TItems from DatabaseHandlerFactory as TDatabase
 * @phpstan-import-type TItems from HookHandlerFactory as THooks
 * @phpstan-import-type TItems from TabHandlerFactory as TTabs
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
     * @param callable(Module $module, TDatabase $items): DatabaseHandlerInterface|null $factoryDatabase
     * @param callable(Module $module, THooks $items): HookHandlerInterface|null $factoryHooks
     * @param callable(Module $module, TTabs $items): TabHandlerInterface|null $factoryTabs
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

        foreach ($handlers as $name => &$items) {
            if ('database' === $name) {
                $items = $factoryDatabase($module, $items);
                continue;
            }

            if ('hooks' === $name) {
                $items = $factoryHooks($module, $items);
                continue;
            }

            if ('tabs' === $name) {
                $items = $factoryTabs($module, $items);
                continue;
            }

            unset($handlers[$name]);
        }

        $handlers = \array_values($handlers);

        return new Installer($handlers);
    }

    /**
     * @param Module $module
     * @param TDatabase $items
     *
     * @return DatabaseHandlerInterface
     */
    private static function defaultDatabaseFactory(Module $module, array $items)
    {
        return DatabaseHandlerFactory::create($module, $items);
    }

    /**
     * @param Module $module
     * @param THooks $items
     *
     * @return HookHandlerInterface
     */
    private static function defaultHooksFactory(Module $module, array $items)
    {
        return HookHandlerFactory::create($module, $items);
    }

    /**
     * @param Module $module
     * @param TTabs $items
     *
     * @return TabHandlerInterface
     */
    private static function defaultTabsFactory(Module $module, array $items)
    {
        return TabHandlerFactory::create($module, $items);
    }
}
