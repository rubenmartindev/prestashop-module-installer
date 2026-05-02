<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook;

use Module;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\HandlerInstallerInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\Exception\HookHandlerInstallerException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\Item\HookItemInterface;

/**
 * @phpstan-type TBuild array{
 *   name: string,
 * }[]
 */
interface HookHandlerInstallerInterface extends HandlerInstallerInterface
{
    /**
     * @param Module $module
     * @param TBuild $hooks
     * @param callable|null $factory
     *
     * @return static
     */
    public static function build(Module $module, array $hooks, $factory = null);

    /**
     * @param HookItemInterface $hookItem
     *
     * @return static
     */
    public function addHook(HookItemInterface $hookItem);

    /**
     * @param string $hookName
     *
     * @return HookItemInterface|null
     */
    public function getHook($hookName);

    /**
     * @param string $hookName
     *
     * @return static
     */
    public function removeHook($hookName);

    /**
    * @return array<string, HookItemInterface>
    */
    public function getHooks();

    /**
     * {@inheritDoc}
     *
     * @throws HookHandlerInstallerException
     */
    public function install();

    /**
     * {@inheritDoc}
     *
     * @throws HookHandlerInstallerException
     */
    public function uninstall();
}
