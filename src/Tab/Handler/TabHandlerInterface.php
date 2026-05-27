<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tab\Handler;

use RubenMartinDev\PrestaShopModuleInstaller\Handler\HandlerInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\Handler\Exception\FailedToCreateTabException;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\Handler\Exception\FailedToDeleteTabException;

interface TabHandlerInterface extends HandlerInterface
{
    /**
     * {@inheritDoc}
     *
     * @throws FailedToCreateTabException
     */
    public function install();

    /**
     * {@inheritDoc}
     *
     * @throws FailedToDeleteTabException
     */
    public function uninstall();
}
