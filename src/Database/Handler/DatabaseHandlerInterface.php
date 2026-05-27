<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Database\Handler;

use Module;
use RubenMartinDev\PrestaShopModuleInstaller\Database\Handler\Exception\FailedToExecuteQueryException;
use RubenMartinDev\PrestaShopModuleInstaller\Database\ValueObject\KeepData;
use RubenMartinDev\PrestaShopModuleInstaller\Database\ValueObject\Query;
use RubenMartinDev\PrestaShopModuleInstaller\Database\ValueObject\QueryFile;
use RubenMartinDev\PrestaShopModuleInstaller\Database\ValueObject\TableName;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\HandlerInterface;

/**
 * @phpstan-import-type TParamTableName from TableName
 * @phpstan-import-type TParamQuery from Query
 * @phpstan-import-type TParamQueryFile from QueryFile
 * @phpstan-import-type TParamKeepData from KeepData
 *
 * @phpstan-type TItem array{
 *   tableName: TParamTableName,
 *   query?: TParamQuery,
 *   queryFile?: TParamQueryFile,
 *   keepData?: TParamKeepData,
 * }
 */
interface DatabaseHandlerInterface extends HandlerInterface
{
    /**
     * {@inheritDoc}
     *
     * @param TItem[] $items
     */
    public static function createFrom(Module $module, array $items);

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
