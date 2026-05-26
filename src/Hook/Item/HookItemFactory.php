<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Hook\Item;

use RubenMartinDev\PrestaShopModuleInstaller\Hook\ValueObject\Name;
use RubenMartinDev\PrestaShopModuleInstaller\Hook\ValueObject\PrestaShopVersion;

/**
 * @phpstan-import-type TParamVersion from PrestaShopVersion
 *
 * @phpstan-type TName string
 * @phpstan-type TPrestaShopVersion TParamVersion
 */
final class HookItemFactory
{
    /**
     * @param TName $name
     * @param TPrestaShopVersion $prestashopVersion
     *
     * @return HookItemInterface
     */
    public static function create(
        $name,
        $prestashopVersion = null
    ) {
        return new HookItem(
            new Name($name),
            new PrestaShopVersion($prestashopVersion)
        );
    }
}
