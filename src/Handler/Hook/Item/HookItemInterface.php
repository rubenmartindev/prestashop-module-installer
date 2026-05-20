<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\Item;

/**
 * @phpstan-type TPrestaShopVersion array{min: string|null, max:string|null}
 */
interface HookItemInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return TPrestaShopVersion
     */
    public function getPrestaShopVersion();
}
