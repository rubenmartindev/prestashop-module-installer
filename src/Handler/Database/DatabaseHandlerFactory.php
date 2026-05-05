<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Handler\Database;

use Module;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Database\Item\DatabaseItem;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Database\Item\DatabaseItemInterface;

/**
 * @phpstan-type TQuery array{
 *   tableName: string,
 *   queryFile: string,
 *   keepData?: bool,
 * }
 * @phpstan-type TQueries TQuery[]
 */
class DatabaseHandlerFactory
{
    /**
     * @param Module $module
     * @param TQueries $queries
     * @param callable(TQuery $query): DatabaseItemInterface|null $factory
     *
     * @return DatabaseHandlerInterface
     */
    public static function create(
        Module $module,
        array $queries,
        $factory = null
    ) {
        $factory = \is_callable($factory) ? $factory : [self::class, 'defaultFactory'];

        $queries = \array_map($factory, $queries);

        return new DatabaseHandler($module, $queries);
    }

    /**
     * @param TQuery $query
     *
     * @return DatabaseItemInterface
     */
    private static function defaultFactory(array $query)
    {
        $arguments = [
            isset($query['tableName']) ? $query['tableName'] : '',
            isset($query['queryFile']) ? $query['queryFile'] : '',
        ];

        if (isset($query['keepData'])) {
            $arguments[] = $query['keepData'];
        }

        return new DatabaseItem(...$arguments);
    }
}
