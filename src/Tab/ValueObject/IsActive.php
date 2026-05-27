<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject;

use RubenMartinDev\PrestaShopModuleInstaller\ValueObject\ValueObjectInterface;

/**
 * @phpstan-type TIsActive bool
 * @phpstan-type TParamIsActive TIsActive
 */
final class IsActive implements ValueObjectInterface
{
    /** @var TIsActive */
    private $isActive;

    /**
     * @param TParamIsActive $isActive
     */
    public function __construct($isActive)
    {
        $this->isActive = (bool) $isActive;
    }

    /**
     * {@inheritDoc}
     *
     * @return TIsActive
     */
    public function getValue()
    {
        return $this->isActive;
    }
}
