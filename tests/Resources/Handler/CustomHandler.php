<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Resources\Handler;

use Module;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\HandlerInterface;

final class CustomHandler implements HandlerInterface
{
    /**
     * {@inheritDoc}
     */
    public static function createFrom(Module $module, array $items)
    {
        return new static();
    }

    /**
     * {@inheritDoc}
     */
    public function install()
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function uninstall()
    {
        return true;
    }
}
