<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Database\ValueObject;

use RubenMartinDev\PrestaShopModuleInstaller\ValueObject\ValueObjectInterface;

/**
 * @phpstan-type TKeepData bool
 * @phpstan-type TParamKeepData TKeepData
 */
final class KeepData implements ValueObjectInterface
{
    /** @var TKeepData */
    private $keepData;

    /**
     * @param TParamKeepData $keepData
     */
    public function __construct($keepData)
    {
        $this->keepData = (bool) $keepData;
    }

    /**
     * {@inheritDoc}
     *
     * @return TKeepData
     */
    public function getValue()
    {
        return $this->keepData;
    }
}
