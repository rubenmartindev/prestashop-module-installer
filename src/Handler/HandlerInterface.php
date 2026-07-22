<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Handler;

use Module;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Exception\HandlerException;
use RubenMartinDev\PrestaShopModuleInstaller\Item\ItemInterface;

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
     * @return ItemInterface[]
     */
    public function getItems();

    /**
     * @param ItemInterface $item
     * @param int|null $position
     *
     * @return static
     */
    public function addItem(ItemInterface $item, $position = null);

    /**
     * @param int $position
     *
     * @return static
     */
    public function removeItem($position);

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
