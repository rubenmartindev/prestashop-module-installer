<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Database\Item;

use RubenMartinDev\PrestaShopModuleInstaller\Database\ValueObject\KeepData;
use RubenMartinDev\PrestaShopModuleInstaller\Database\ValueObject\Query;
use RubenMartinDev\PrestaShopModuleInstaller\Database\ValueObject\QueryFile;
use RubenMartinDev\PrestaShopModuleInstaller\Database\ValueObject\TableName;
use RubenMartinDev\PrestaShopModuleInstaller\Item\ItemInterface;

interface DatabaseItemInterface extends ItemInterface
{
    const PLACEHOLDERS = [
        '{{DB_PREFIX}}'   => \_DB_PREFIX_,
        '{{ENGINE_TYPE}}' => \_MYSQL_ENGINE_,
    ];

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
