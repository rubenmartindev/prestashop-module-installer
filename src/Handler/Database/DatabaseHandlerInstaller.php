<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Handler\Database;

use Db;
use Module;
use PrestaShopDatabaseException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\AbstractHandlerInstaller;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Database\Exception\DatabaseHandlerInstallerException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Database\Exception\FailedToExecuteQueryException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Database\Exception\QueriesIsEmptyException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Database\Exception\QueriesMustBeInstanceOfDatabaseItemException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Database\Item\DatabaseItem;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Database\Item\DatabaseItemInterface;

class DatabaseHandlerInstaller extends AbstractHandlerInstaller implements DatabaseHandlerInstallerInterface
{
    /** @var array<string, DatabaseItemInterface> */
    protected $queries = [];

    /**
     * @param Module $module
     * @param DatabaseItemInterface[] $queries
     */
    public function __construct(Module $module, array $queries)
    {
        parent::__construct($module);

        $this->ensureQueriesIsValid($queries);

        foreach ($queries as $query) {
            $this->addQuery($query);
        }
    }

    /**
     * {@inheritDoc}
     */
    public static function build(Module $module, array $queries, $factory = null)
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

        return new static($module, $queries);
    }

    /**
     * {@inheritDoc}
     */
    public function addQuery(DatabaseItemInterface $query)
    {
        $this->queries[$query->getTableName()] = $query;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getQuery($tableName)
    {
        return isset($this->queries[$tableName])
            ? $this->queries[$tableName]
            : null
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function removeQuery($tableName)
    {
        if (isset($this->queries[$tableName])) {
            unset($this->queries[$tableName]);
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getQueries()
    {
        return $this->queries;
    }

    /**
     * {@inheritDoc}
     */
    public function install()
    {
        foreach ($this->queries as $query) {
            $this->executeSQL($query->getQuery());
        }

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function uninstall()
    {
        foreach ($this->queries as $query) {
            if ($query->getKeepData()) {
                continue;
            }

            $query = \sprintf('DROP TABLE IF EXISTS `%s`', _DB_PREFIX_ . $query->getTableName());

            $this->executeSQL($query);
        }

        return true;
    }

    /**
     * @param string $query
     *
     * @return void
     *
     * @throws FailedToExecuteQueryException
     */
    protected function executeSQL($query)
    {
        try {
            Db::getInstance()->execute($query);
        } catch (PrestaShopDatabaseException $e) {
            throw new FailedToExecuteQueryException(
                \sprintf('An error occurred while executing the query: %s', $query)
            );
        }
    }

    /**
     * @param DatabaseItemInterface[] $queries
     *
     * @return void
     *
     * @throws QueriesIsEmptyException
     * @throws QueriesMustBeInstanceOfDatabaseItemException
     */
    private function ensureQueriesIsValid(array $queries)
    {
        if (empty($queries)) {
            throw new QueriesIsEmptyException('The $queries cannot be empty');
        }

        foreach ($queries as $query) {
            if (!$query instanceof DatabaseItemInterface) {
                throw new QueriesMustBeInstanceOfDatabaseItemException(
                    'The $queries mus be an array of DatabaseItemInterface'
                );
            }
        }
    }
}
