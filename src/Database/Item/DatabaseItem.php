<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Database\Item;

use RubenMartinDev\PrestaShopModuleInstaller\Database\Item\Exception\SQLIsEmptyException;
use RubenMartinDev\PrestaShopModuleInstaller\Database\ValueObject\KeepData;
use RubenMartinDev\PrestaShopModuleInstaller\Database\ValueObject\Query;
use RubenMartinDev\PrestaShopModuleInstaller\Database\ValueObject\QueryFile;
use RubenMartinDev\PrestaShopModuleInstaller\Database\ValueObject\TableName;

final class DatabaseItem implements DatabaseItemInterface
{
    const TYPE = 'database';

    /** @var TableName */
    private $tableName;

    /** @var Query */
    private $query;

    /** @var QueryFile */
    private $queryFile;

    /** @var KeepData */
    private $keepData;

    /** @var string|null */
    private $sql = null;

    public function __construct(
        TableName $tableName,
        Query $query,
        QueryFile $queryFile,
        KeepData $keepData
    ) {
        $this->tableName    = $tableName;
        $this->query        = $query;
        $this->queryFile    = $queryFile;
        $this->keepData     = $keepData;
    }

    /**
     * {@inheritDoc}
     */
    public static function createFrom(
        $tableName,
        $query = null,
        $queryFile = null,
        $keepData = false
    ) {
        return new static(
            new TableName($tableName),
            new Query($query),
            new QueryFile($queryFile),
            new KeepData($keepData)
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getType()
    {
        return self::TYPE;
    }

    /**
     * {@inheritDoc}
     */
    public function getTableName()
    {
        return $this->tableName;
    }

    /**
     * {@inheritDoc}
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * {@inheritDoc}
     */
    public function getQueryFile()
    {
        return $this->queryFile;
    }

    /**
     * {@inheritDoc}
     */
    public function getKeepData()
    {
        return $this->keepData;
    }

    /**
     * {@inheritDoc}
     */
    public function getSQL()
    {
        if (null !== $this->sql) {
            return $this->sql;
        }

        $sql = null;

        if (false === $this->query->isEmpty()) {
            $sql = $this->query->getValue();
        }

        if (false === $this->queryFile->isEmpty()) {
            $sql = \file_get_contents($this->queryFile->getValue());
            $sql = \trim($sql);
        }

        if (true === empty($sql)) {
            throw new SQLIsEmptyException('The SQL is empty');
        }

        foreach (self::PLACEHOLDERS as $placeholder => $value) {
            $sql = \str_replace($placeholder, $value, $sql);
        }

        return $this->sql = $sql;
    }
}
