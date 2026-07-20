<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Item;

use Module;

interface ItemInterface
{
    /**
     * @param Module $module
     * @param array $properties
     *
     * @return static
     */
    public static function createFrom(Module $module, array $properties);

    /**
     * @return string
     */
    public function getType();
}
