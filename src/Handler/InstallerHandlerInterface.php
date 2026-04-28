<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Handler;

interface InstallerHandlerInterface
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
