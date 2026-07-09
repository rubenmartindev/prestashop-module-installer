<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Resources\Handler;

use Module;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\HandlerInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Item\ItemInterface;

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
    public function getItems()
    {
        return [];
    }

    /**
     * {@inheritDoc}
     */
    public function addItem(ItemInterface $item, $position = null)
    {
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function removeItem($position)
    {
        return $this;
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
