<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Handler;

use RubenMartinDev\PrestaShopModuleInstaller\Handler\Exception\HandlerException;

interface HandlerInterface
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
