<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Configuration\ValueObject;

use RubenMartinDev\PrestaShopModuleInstaller\ValueObject\ValueObjectInterface;

/**
 * @phpstan-type TValue mixed
 * @phpstan-type TParamValue TValue
 */
final class Value implements ValueObjectInterface
{
    /** @var TValue */
    private $value;

    /**
     * @param TParamValue $value
     */
    public function __construct($value)
    {
        $this->value = $this->formatter($value);
    }

    /**
     * {@inheritDoc}
     *
     * @return TValue
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param TParamValue $value
     *
     * @return TValue
     */
    private function formatter($value)
    {
        if (true === \is_bool($value)) {
            return $value ? '1' : '0';
        }

        if (true === \is_string($value)) {
            return \trim($value);
        }

        if (true === \is_array($value)) {
            return \json_encode($value);
        }

        if (true === \is_object($value)) {
            return \serialize($value);
        }

        return $value;
    }
}
