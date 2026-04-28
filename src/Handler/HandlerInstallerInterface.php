<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Handler;

interface HandlerInstallerInterface
{
    /**
     * @return bool
     */
    public function install();

    /**
     * @return bool
     */
    public function uninstall();
}
