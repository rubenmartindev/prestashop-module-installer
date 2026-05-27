<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Database\Handler;

use Module;
use RubenMartinDev\PrestaShopModuleInstaller\Database\Item\DatabaseItem;
use RubenMartinDev\PrestaShopModuleInstaller\Database\Item\DatabaseItemInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Database\ValueObject\KeepData;
use RubenMartinDev\PrestaShopModuleInstaller\Database\ValueObject\Query;
use RubenMartinDev\PrestaShopModuleInstaller\Database\ValueObject\QueryFile;
use RubenMartinDev\PrestaShopModuleInstaller\Database\ValueObject\TableName;

/**
 * @phpstan-import-type TParamTableName from TableName
 * @phpstan-import-type TParamQuery from Query
 * @phpstan-import-type TParamQueryFile from QueryFile
 * @phpstan-import-type TParamKeepData from KeepData
 *
 * @phpstan-type TItem array{
 *   tableName: TParamTableName,
 *   query?: TParamQuery,
 *   queryFile?: TParamQueryFile,
 *   keepData?: TParamKeepData,
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
        $defaultArguments = [
            'tableName' => '',
            'query'     => null,
            'queryFile' => null,
            'keepData'  => false,
        ];

        $arguments = \array_merge($defaultArguments, $item);
        $arguments = \array_values($arguments);

        return DatabaseItem::createFrom(...$arguments);
    }
}
