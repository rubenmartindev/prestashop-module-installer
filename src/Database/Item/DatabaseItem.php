<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Database\Item;

use Module;
use RubenMartinDev\PrestaShopModuleInstaller\Database\Item\Exception\SQLIsEmptyException;
use RubenMartinDev\PrestaShopModuleInstaller\Database\ValueObject\KeepData;
use RubenMartinDev\PrestaShopModuleInstaller\Database\ValueObject\Query;
use RubenMartinDev\PrestaShopModuleInstaller\Database\ValueObject\QueryFile;
use RubenMartinDev\PrestaShopModuleInstaller\Database\ValueObject\TableName;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @phpstan-import-type TParamTableName from TableName
 * @phpstan-import-type TParamQuery from Query
 * @phpstan-import-type TParamQueryFile from QueryFile
 * @phpstan-import-type TParamKeepData from KeepData
 */
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
     *
     * @param array{
     *   table_name: TParamTableName,
     *   query?: TParamQuery,
     *   query_file?: TParamQueryFile,
     *   keep_data?: TParamKeepData,
     * } $properties
     */
    public static function createFrom(Module $module, array $properties)
    {
        $properties = self::createOptionsResolver($module)->resolve($properties);

        return new static(
            $properties['table_name'],
            $properties['query'],
            $properties['query_file'],
            $properties['keep_data']
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

    /**
     * @param Module $module
     *
     * @return OptionsResolver
     */
    private static function createOptionsResolver(Module $module)
    {
        $resolver = new OptionsResolver();

        $resolver->setRequired('table_name');

        $resolver->setDefault('query', null);
        $resolver->setDefault('query_file', null);
        $resolver->setDefault('keep_data', false);

        $resolver->setAllowedTypes('table_name', 'string');
        $resolver->setAllowedTypes('query', ['string', 'null']);
        $resolver->setAllowedTypes('query_file', ['string', 'null']);
        $resolver->setAllowedTypes('keep_data', 'bool');

        $resolver->setNormalizer('table_name', function (Options $options, $value) {
            return new TableName($value);
        });
        $resolver->setNormalizer('query', function (Options $options, $value) {
            return new Query($value);
        });
        $resolver->setNormalizer('query_file', function (Options $options, $value) {
            return new QueryFile($value);
        });
        $resolver->setNormalizer('keep_data', function (Options $options, $value) {
            return new KeepData($value);
        });

        return $resolver;
    }
}
