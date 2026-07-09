<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Hook\Item;

use RubenMartinDev\PrestaShopModuleInstaller\Hook\ValueObject\Name;
use RubenMartinDev\PrestaShopModuleInstaller\Hook\ValueObject\PrestaShopVersion;

final class HookItem implements HookItemInterface
{
    const TYPE = 'hook';

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
    public static function createFrom(
        $name,
        $prestashopVersion = null
    ) {
        return new static(
            new Name($name),
            new PrestaShopVersion($prestashopVersion)
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getType()
    {
        return self::TYPE;
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
