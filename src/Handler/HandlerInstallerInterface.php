<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Handler;

use RubenMartinDev\PrestaShopModuleInstaller\Handler\Exception\HandlerInstallerException;

interface HandlerInstallerInterface
{
    /**
     * @return bool
     *
     * @throws HandlerInstallerException
     */
    public function install();

    /**
     * @return bool
     *
     * @throws HandlerInstallerException
     */
    public function uninstall();
}
