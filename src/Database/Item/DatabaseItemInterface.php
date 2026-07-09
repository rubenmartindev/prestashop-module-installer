<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Database\Item;

use RubenMartinDev\PrestaShopModuleInstaller\Database\ValueObject\KeepData;
use RubenMartinDev\PrestaShopModuleInstaller\Database\ValueObject\Query;
use RubenMartinDev\PrestaShopModuleInstaller\Database\ValueObject\QueryFile;
use RubenMartinDev\PrestaShopModuleInstaller\Database\ValueObject\TableName;
use RubenMartinDev\PrestaShopModuleInstaller\Item\ItemInterface;

/**
 * @phpstan-import-type TParamTableName from TableName
 * @phpstan-import-type TParamQuery from Query
 * @phpstan-import-type TParamQueryFile from QueryFile
 * @phpstan-import-type TParamKeepData from KeepData
 */
interface DatabaseItemInterface extends ItemInterface
{
    const PLACEHOLDERS = [
        '{{DB_PREFIX}}'   => \_DB_PREFIX_,
        '{{ENGINE_TYPE}}' => \_MYSQL_ENGINE_,
    ];

    /**
     * @param TParamTableName $tableName
     * @param TParamQuery $query
     * @param TParamQueryFile $queryFile
     * @param TParamKeepData $keepData
     *
     * @return static
     */
    public static function createFrom(
        $tableName,
        $query = null,
        $queryFile = null,
        $keepData = false
    );

    /**
     * @return TableName
     */
    public function getTableName();

    /**
     * @return Query
     */
    public function getQuery();

    /**
     * @return QueryFile
     */
    public function getQueryFile();

    /**
     * @return KeepData
     */
    public function getKeepData();

    /**
     * @return string
     */
    public function getSQL();
}
