<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Handler\Database;

use RubenMartinDev\PrestaShopModuleInstaller\Handler\Database\Exception\FailedToExecuteQueryException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Database\Item\DatabaseItemInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\HandlerInstallerInterface;

interface DatabaseHandlerInstallerInterface extends HandlerInstallerInterface
{
    /**
     * @param DatabaseItemInterface $query
     *
     * @return static
     */
    public function addQuery(DatabaseItemInterface $query);

    /**
     * @param string $tableName
     *
     * @return DatabaseItemInterface|null
     */
    public function getQuery($tableName);

    /**
     * @param string $tableName
     *
     * @return static
     */
    public function removeQuery($tableName);

    /**
     * @return DatabaseItemInterface[]
     */
    public function getQueries();

    /**
     * {@inheritDoc}
     *
     * @throws FailedToExecuteQueryException
     */
    public function install();

    /**
     * {@inheritDoc}
     *
     * @throws FailedToExecuteQueryException
     */
    public function uninstall();
}
