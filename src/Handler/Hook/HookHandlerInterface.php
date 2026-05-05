<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook;

use RubenMartinDev\PrestaShopModuleInstaller\Handler\HandlerInstallerInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\Exception\HookHandlerException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\Item\HookItemInterface;

interface HookHandlerInterface extends HandlerInstallerInterface
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
    * @return array<string, HookItemInterface>
    */
    public function getHooks();

    /**
     * {@inheritDoc}
     *
     * @throws HookHandlerException
     */
    public function install();

    /**
     * {@inheritDoc}
     *
     * @throws HookHandlerException
     */
    public function uninstall();
}
