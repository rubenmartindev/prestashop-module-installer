<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Hook\Item;

use RubenMartinDev\PrestaShopModuleInstaller\Hook\ValueObject\Name;
use RubenMartinDev\PrestaShopModuleInstaller\Hook\ValueObject\PrestaShopVersion;

interface HookItemInterface
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
