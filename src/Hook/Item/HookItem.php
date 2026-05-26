<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Hook\Item;

use RubenMartinDev\PrestaShopModuleInstaller\Hook\ValueObject\Name;
use RubenMartinDev\PrestaShopModuleInstaller\Hook\ValueObject\PrestaShopVersion;

final class HookItem implements HookItemInterface
{
    /** @var Name */
    private $name;

    /** @var PrestaShopVersion */
    private $prestashopVersion;

    public function __construct(
        Name $name,
        PrestaShopVersion $prestashopVersion
    ) {
        $this->name                 = $name;
        $this->prestashopVersion    = $prestashopVersion;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function getPrestaShopVersion()
    {
        return $this->prestashopVersion;
    }
}
