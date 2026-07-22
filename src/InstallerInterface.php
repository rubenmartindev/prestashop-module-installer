<?php

namespace RubenMartinDev\PrestaShopModuleInstaller;

use Module;
use RubenMartinDev\PrestaShopModuleInstaller\Configuration\Handler\ConfigurationHandlerInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Database\Handler\DatabaseHandlerInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Exception\HandlerException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\HandlerInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Hook\Handler\HookHandlerInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\Handler\TabHandlerInterface;

/**
 * @phpstan-import-type TItem from DatabaseHandlerInterface as TDatabase
 * @phpstan-import-type TItem from HookHandlerInterface as THook
 * @phpstan-import-type TItem from TabHandlerInterface as TTab
 * @phpstan-import-type TItem from ConfigurationHandlerInterface as TConfiguration
 *
 * @phpstan-type THandlers array{
 *   configuration?: TConfiguration[],
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
     * @return array<int, HandlerInterface>
     */
    public function getHandlers();

    /**
     * @param HandlerInterface $handler
     * @param int|null $position
     *
     * @return static
     */
    public function addHandler(HandlerInterface $handler, $position = null);

    /**
     * @param int $position
     *
     * @return static
     */
    public function removeHandler($position);

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
