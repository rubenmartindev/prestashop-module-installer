<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Database\Item;

use RubenMartinDev\PrestaShopModuleInstaller\Database\ValueObject\KeepData;
use RubenMartinDev\PrestaShopModuleInstaller\Database\ValueObject\Query;
use RubenMartinDev\PrestaShopModuleInstaller\Database\ValueObject\QueryFile;
use RubenMartinDev\PrestaShopModuleInstaller\Database\ValueObject\TableName;

/**
 * @phpstan-type TIableName string
 * @phpstan-type TQuery string|null
 * @phpstan-type TQueryFile string|null
 * @phpstan-type TKeepData bool
 */
final class DatabaseItemFactory
{
    /**
     * @param TTableName $tableName
     * @param TQuery $query
     * @param TQueryFile $queryFile
     * @param TKeepData $keepData
     *
     * @return DatabaseItemInterface
     */
    public static function create(
        $tableName,
        $query = null,
        $queryFile = null,
        $keepData = false
    ) {
        return new DatabaseItem(
            new TableName($tableName),
            new Query($query),
            new QueryFile($queryFile),
            new KeepData($keepData)
        );
    }
}
