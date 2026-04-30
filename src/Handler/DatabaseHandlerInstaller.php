<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Handler;

use Db;
use Module;
use PrestaShopDatabaseException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Exception\DatabaseHandlerInstallerException;

/**
 * @phpstan-type TQueries array<string, array{
 *   query?: string,
 *   file?: string,
 *   keep_data?: bool,
 * }>
 */
class DatabaseHandlerInstaller extends AbstractHandlerInstaller
{
    const DEFAULT_QUERY_PROPERTY = [
        'keep_data' => false,
        'query'     => null,
        'file'      => null,
    ];

    const PLACEHOLDERS = [
        '{{DB_PREFIX}}'   => \_DB_PREFIX_,
        '{{ENGINE_TYPE}}' => \_MYSQL_ENGINE_,
    ];

    /** @var TQueries */
    protected $queries;

    /**
     * @param Module $module
     * @param TQueries $queries
     */
    public function __construct(Module $module, array $queries)
    {
        parent::__construct($module);

        $this->queries = \array_map(function (array $queryProperties) {
            return \array_merge(self::DEFAULT_QUERY_PROPERTY, $queryProperties);
        }, $queries);
    }

    /**
     * {@inheritDoc}
     *
     * @throws DatabaseHandlerInstallerException
     */
    public function install()
    {
        foreach ($this->queries as $table => $queryProperties) {
            $query = null;

            if (isset($queryProperties['query'])) {
                $query = $queryProperties['query'];
            }

            if (isset($queryProperties['file'])) {
                $query = $this->getSQLFromFile($queryProperties['file']);
            }

            $query = \trim((string) $query);

            if ($query) {
                $this->replacePlaceholders($query);
                $this->executeSQL($query);
            }
        }

        return true;
    }

    /**
     * {@inheritDoc}
     *
     * @throws DatabaseHandlerInstallerException
     */
    public function uninstall()
    {
        foreach ($this->queries as $table => $queryProperties) {
            if ($queryProperties['keep_data']) {
                continue;
            }

            $query = sprintf('DROP TABLE IF EXISTS `%s`', _DB_PREFIX_ . $table);

            $this->executeSQL($query);
        }

        return true;
    }

    /**
     * @param string $filePath
     *
     * @return string
     *
     * @throws DatabaseHandlerInstallerException
     */
    protected function getSQLFromFile($filePath)
    {
        if (!\file_exists($filePath)) {
            throw new DatabaseHandlerInstallerException(\sprintf('The file %s does not exist', $filePath));
        }

        if (!\is_readable($filePath)) {
            throw new DatabaseHandlerInstallerException(\sprintf('The file %s is not readable', $filePath));
        }

        $query = \file_get_contents($filePath);

        if (!$query) {
            throw new DatabaseHandlerInstallerException(\sprintf('The file %s does not contain any data', $filePath));
        }

        return $query;
    }

    /**
     * @param string $query
     *
     * @return string
     */
    protected function replacePlaceholders($query)
    {
        foreach (self::PLACEHOLDERS as $placeholder => $value) {
            $query = \str_replace($placeholder, $value, $query);
        }

        return $query;
    }

    /**
     * @param string $query
     *
     * @return void
     *
     * @throws DatabaseHandlerInstallerException
     */
    protected function executeSQL($query)
    {
        try {
            Db::getInstance()->execute($query);
        } catch (PrestaShopDatabaseException $e) {
            throw new DatabaseHandlerInstallerException(\sprintf('An error occurred while executing the query: %s', $query));
        }
    }
}
