<?php

namespace RubenMartinDev\PrestaShopModuleInstaller;

use Module;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Database\DatabaseHandlerInstallerInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\HandlerInstallerInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\HookHandlerInstallerInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\TabHandlerInstallerInterface;

/**
 * @phpstan-import-type TBuild from DatabaseHandlerInstallerInterface as TDatabaseBuild
 * @phpstan-import-type TBuild from HookHandlerInstallerInterface as THooksBuild
 * @phpstan-import-type TBuild from TabHandlerInstallerInterface as TTabsBuild
 */
interface InstallerInterface
{
    /**
     * @param Module $module
     * @param array{
     *   database?: TDatabaseBuild,
     *   hooks?: THooksBuild,
     *   tabs?: TTabsBuild,
     * } $handlers
     * @param callable|null $factoryDatabase
     * @param callable|null $factoryHooks
     * @param callable|null $factoryTabs
     *
     * @return static
     */
    public static function build(
        Module $module,
        array $handlers,
        $factoryDatabase = null,
        $factoryHooks = null,
        $factoryTabs = null
    );

    /**
     * @param int $priority
     * @param HandlerInstallerInterface $handler
     *
     * @return static
     */
    public function addHandler($priority, HandlerInstallerInterface $handler);

    /**
     * @param int $priority
     *
     * @return HandlerInstallerInterface|null
     */
    public function getHandler($priority);

    /**
     * @param int $priority
     *
     * @return static
     */
    public function removeHandler($priority);

    /**
     * @return HandlerInstallerInterface[]
     */
    public function getHandlers();

    /**
     * @return bool
     */
    public function install();

    /**
     * @return bool
     */
    public function uninstall();
}
