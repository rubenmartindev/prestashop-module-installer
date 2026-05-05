<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Handler\Database\Item;

use RubenMartinDev\PrestaShopModuleInstaller\Handler\Database\Item\Exception\QuerFileNotExistsException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Database\Item\Exception\QueryFileIsEmptyException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Database\Item\Exception\QueryFileIsNotRedeableException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Database\Item\Exception\QueryFileIsNotStringException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Database\Item\Exception\TableNameIsEmptyException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Database\Item\Exception\TableNameIsNotStringException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Database\Item\Exception\TableNameIsNotValidException;
use Validate;

class DatabaseItem implements DatabaseItemInterface
{
    const PLACEHOLDERS = [
        '{{DB_PREFIX}}'   => \_DB_PREFIX_,
        '{{ENGINE_TYPE}}' => \_MYSQL_ENGINE_,
    ];

    /** @var string */
    private $tableName;

    /** @var string|null */
    private $query = null;

    /** @var string */
    private $queryFile;

    /** @var bool */
    private $keepData;

    /**
     * @param string $tableName
     * @param string $queryFile
     * @param bool $keepData
     */
    public function __construct(
        $tableName,
        $queryFile,
        $keepData = false
    ) {
        $this->ensureTableNameIsValid($tableName);
        $this->ensureQueryFileIsValid($queryFile);

        $this->tableName    = $tableName;
        $this->queryFile    = $queryFile;
        $this->keepData     = (bool) $keepData;
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
     *
     * @throws QueryFileIsEmptyException
     */
    public function getQuery()
    {
        if (null !== $this->query) {
            return $this->query;
        }

        $query = \file_get_contents($this->queryFile);
        $query = \trim($query);

        if (empty($query)) {
            throw new QueryFileIsEmptyException('The $queryFile does not contain any data');
        }

        foreach (self::PLACEHOLDERS as $placeholder => $value) {
            $query = \str_replace($placeholder, $value, $query);
        }

        return $this->query = $query;
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
     * @param string $tableName
     *
     * @return void
     *
     * @throws TableNameIsNotStringException
     * @throws TableNameIsEmptyException
     * @throws TableNameIsNotValidException
     */
    private function ensureTableNameIsValid($tableName)
    {
        if (!\is_string($tableName)) {
            throw new TableNameIsNotStringException('The $tableName is not a string');
        }

        if (empty($tableName)) {
            throw new TableNameIsEmptyException('The $tableName is empty');
        }

        if (!Validate::isTableOrIdentifier($tableName)) {
            throw new TableNameIsNotValidException('The $tableName is not valid');
        }
    }

    /**
     * @param string $queryFile
     *
     * @return void
     *
     * @throws QueryFileIsNotStringException
     * @throws QuerFileNotExistsException
     * @throws QueryFileIsNotRedeableException
     */
    private function ensureQueryFileIsValid($queryFile)
    {
        if (!\is_string($queryFile)) {
            throw new QueryFileIsNotStringException('The $queryFile is not a string');
        }

        if (!\file_exists($queryFile)) {
            throw new QuerFileNotExistsException('The $queryFile does not exists');
        }

        if (!\is_readable($queryFile)) {
            throw new QueryFileIsNotRedeableException('The $queryFil is not readable');
        }
    }
}
