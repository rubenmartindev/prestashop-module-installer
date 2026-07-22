<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Configuration\Item;

use RubenMartinDev\PrestaShopModuleInstaller\Configuration\ValueObject\Name;
use RubenMartinDev\PrestaShopModuleInstaller\Configuration\ValueObject\Value;
use RubenMartinDev\PrestaShopModuleInstaller\Item\ItemInterface;

interface ConfigurationItemInterface extends ItemInterface
{
    /**
     * @return Name
     */
    public function getName();

    /**
     * @return Value
     */
    public function getValue();
}
