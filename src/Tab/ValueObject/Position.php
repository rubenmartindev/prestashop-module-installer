<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject;

use RubenMartinDev\PrestaShopModuleInstaller\ValueObject\ValueObjectInterface;

/**
 * @phpstan-type TPosition int
 * @phpstan-type TParamPosition TPosition
 */
final class Position implements ValueObjectInterface
{
    /** @var TPosition */
    private $position;

    /**
     * @param TParamPosition $position
     */
    public function __construct($position)
    {
        $this->position = (int) $position;
    }

    /**
     * {@inheritDoc}
     *
     * @return TPosition
     */
    public function getValue()
    {
        return $this->position;
    }
}
