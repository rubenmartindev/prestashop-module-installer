<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Handler\Database;

use Module;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Database\Exception\DatabaseHandlerInstallerException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Database\Item\DatabaseItem;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Database\Item\DatabaseItemInterface;

/**
 * @phpstan-type TQuery array{
 *   tableName: string,
 *   queryFile: string,
 *   keepData?: bool,
 * }
 *
 * @phpstan-type TQueries TQuery[]
 */
class DatabaseHandlerInstallerFactory
{
    /**
     * @param Module $module
     * @param TQueries $queries
     * @param callable(TQuery $query): DatabaseItemInterface|null $factory
     *
     * @return DatabaseHandlerInstaller
     */
    public static function create(Module $module, array $queries, $factory = null)
    {
        $factory = \is_callable($factory)
            ? $factory
            : function (array $query) {
                if (!isset($query['tableName'])) {
                    throw new DatabaseHandlerInstallerException('The key tableName is required');
                }

                if (!isset($query['queryFile'])) {
                    throw new DatabaseHandlerInstallerException('The key queryFile is required');
                }

                $query['keepData'] = isset($query['keepData']) ? $query['keepData'] : false;

                return new DatabaseItem(
                    $query['tableName'],
                    $query['queryFile'],
                    $query['keepData']
                );
            }
        ;

        $queries = \array_map($factory, $queries);

        return new DatabaseHandlerInstaller($module, $queries);
    }
}
