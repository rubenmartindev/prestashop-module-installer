<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Database\ValueObject;

use RubenMartinDev\PrestaShopModuleInstaller\ValueObject\ValueObjectInterface;

final class KeepData implements ValueObjectInterface
{
    /** @var bool */
    private $keepData;

    /**
     * @param bool $keepData
     */
    public function __construct($keepData)
    {
        $this->keepData = (bool) $keepData;
    }

    /**
     * {@inheritDoc}
     */
    public function isEquals($value)
    {
        $value = $value instanceof ValueObjectInterface
            ? $value->getValue()
            : $value
        ;

        return $value === $this->keepData;
    }

    /**
     * {@inheritDoc}
     *
     * @return bool
     */
    public function getValue()
    {
        return $this->keepData;
    }
}
