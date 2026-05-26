<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Database\Handler;

use Module;
use RubenMartinDev\PrestaShopModuleInstaller\Database\Item\DatabaseItemFactory;
use RubenMartinDev\PrestaShopModuleInstaller\Database\Item\DatabaseItemInterface;

/**
 * @phpstan-import-type TTableName from DatabaseItemFactory
 * @phpstan-import-type TQuery from DatabaseItemFactory
 * @phpstan-import-type TQueryFile from DatabaseItemFactory
 * @phpstan-import-type TKeepData from DatabaseItemFactory
 *
 * @phpstan-type TItem array{
 *   tableName: TTableName,
 *   query?: TQuery,
 *   queryFile?: TQueryFile,
 *   keepData?: TKeepData,
 * }
 * @phpstan-type TItems TItem[]
 */
final class DatabaseHandlerFactory
{
    /**
     * @param Module $module
     * @param TItems $items
     * @param callable(TItem $item): DatabaseItemInterface|null $factory
     *
     * @return DatabaseHandlerInterface
     */
    public static function create(
        Module $module,
        array $items,
        $factory = null
    ) {
        $factory = \is_callable($factory) ? $factory : [self::class, 'defaultFactory'];

        $items = \array_map($factory, $items);

        return new DatabaseHandler($module, $items);
    }

    /**
     * @param TItem $item
     *
     * @return DatabaseItemInterface
     */
    private static function defaultFactory(array $item)
    {
        $arguments = \array_values($item);

        return DatabaseItemFactory::create(...$arguments);
    }
}
