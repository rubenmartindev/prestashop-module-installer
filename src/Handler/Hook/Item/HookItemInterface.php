<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\Item;

interface HookItemInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return string|null
     */
    public function getPrestaShopVersion();
}
