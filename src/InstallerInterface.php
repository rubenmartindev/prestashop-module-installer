<?php

namespace RubenMartinDev\PrestaShopModuleInstaller;

use RubenMartinDev\PrestaShopModuleInstaller\Handler\Exception\HandlerException;

interface InstallerInterface
{
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
