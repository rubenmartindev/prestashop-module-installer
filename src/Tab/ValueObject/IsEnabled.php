<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject;

use RubenMartinDev\PrestaShopModuleInstaller\ValueObject\ValueObjectInterface;

/**
 * @phpstan-type TIsEnabled bool
 * @phpstan-type TParamIsEnabled TIsEnabled
 */
final class IsEnabled implements ValueObjectInterface
{
    /** @var TIsEnabled */
    private $isEnabled;

    /**
     * @param TParamIsEnabled $isEnabled
     */
    public function __construct($isEnabled)
    {
        $this->isEnabled = (bool) $isEnabled;
    }

    /**
     * {@inheritDoc}
     *
     * @return TIsEnabled
     */
    public function getValue()
    {
        return $this->isEnabled;
    }
}
