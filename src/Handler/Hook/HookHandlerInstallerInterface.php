<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook;

use RubenMartinDev\PrestaShopModuleInstaller\Handler\HandlerInstallerInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\Exception\HookHandlerInstallerException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\Item\HookItemInterface;

interface HookHandlerInstallerInterface extends HandlerInstallerInterface
{
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
    * @return HookItemInterface[]
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
