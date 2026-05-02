<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Handler\Database;

use Module;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Database\Item\DatabaseItemInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\HandlerInstallerInterface;

/**
 * @phpstan-type TBuild array{
 *   tableName: string,
 *   queryFile: string,
 *   keepData?: bool,
 * }[]
 */
interface DatabaseHandlerInstallerInterface extends HandlerInstallerInterface
{
    /**
     * @param Module $module
     * @param TBuild $queries
     * @param callable|null $factory
     *
     * @return static
     */
    public static function build(Module $module, array $queries, $factory = null);

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
