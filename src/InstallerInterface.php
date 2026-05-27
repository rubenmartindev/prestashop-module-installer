<?php

namespace RubenMartinDev\PrestaShopModuleInstaller;

use Module;
use RubenMartinDev\PrestaShopModuleInstaller\Database\Handler\DatabaseHandler;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Exception\HandlerException;
use RubenMartinDev\PrestaShopModuleInstaller\Hook\Handler\HookHandler;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\Handler\TabHandler;

/**
 * @phpstan-import-type TItem from DatabaseHandler as TDatabase
 * @phpstan-import-type TItem from HookHandler as THook
 * @phpstan-import-type TItem from TabHandler as TTab
 *
 * @phpstan-type THandlers array{
 *   database?: TDatabase[],
 *   hooks?: THook[],
 *   tabs?: TTab[],
 * }
 */
interface InstallerInterface
{
    /**
     * @param Module $module
     * @param THandlers $handlers
     *
     * @return static
     */
    public static function createFrom(Module $module, array $handlers);

    /**
     * @return bool
     *
     * @throws HandlerException
     */
    public function install();

    /**
     * @return bool
     *
     * @throws HandlerException
     */
    public function uninstall();
}
