<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Hook\Item;

use RubenMartinDev\PrestaShopModuleInstaller\Hook\ValueObject\Name;
use RubenMartinDev\PrestaShopModuleInstaller\Hook\ValueObject\PrestaShopVersion;

/**
 * @phpstan-import-type TParamName from Name
 * @phpstan-import-type TParamPrestaShopVersion from PrestaShopVersion
 */
interface HookItemInterface
{
    /**
     * @param TParamName $name
     * @param TParamPrestaShopVersion $prestashopVersion
     *
     * @return static
     */
    public static function createFrom(
        $name,
        $prestashopVersion = null
    );

    /**
     * @return Name
     */
    public function getName();

    /**
     * @return PrestaShopVersion
     */
    public function getPrestaShopVersion();
}
