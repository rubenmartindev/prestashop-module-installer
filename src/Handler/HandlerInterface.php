<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Handler;

use Module;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Exception\HandlerException;

interface HandlerInterface
{
    /**
     * @param Module $module
     * @param array $items
     *
     * @return static
     */
    public static function createFrom(Module $module, array $items);

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
