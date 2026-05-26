<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Hook\Handler;

use RubenMartinDev\PrestaShopModuleInstaller\Handler\HandlerInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Hook\Handler\Exception\HookHandlerException;

interface HookHandlerInterface extends HandlerInterface
{
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
