<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Database\Handler;

use RubenMartinDev\PrestaShopModuleInstaller\Database\Handler\Exception\FailedToExecuteQueryException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\HandlerInterface;

interface DatabaseHandlerInterface extends HandlerInterface
{
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
