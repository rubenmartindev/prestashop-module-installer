<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Hook\Item;

use RubenMartinDev\PrestaShopModuleInstaller\Hook\ValueObject\Name;
use RubenMartinDev\PrestaShopModuleInstaller\Hook\ValueObject\PrestaShopVersion;
use RubenMartinDev\PrestaShopModuleInstaller\Item\ItemInterface;

interface HookItemInterface extends ItemInterface
{
    /**
     * @return Name
     */
    public function getName();

    /**
     * @return PrestaShopVersion
     */
    public function getPrestaShopVersion();
}
