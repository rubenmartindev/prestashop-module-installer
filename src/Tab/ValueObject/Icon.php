<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject;

use RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject\Exception\IconIsEmptyException;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject\Exception\IconTypeIsInvalidException;
use RubenMartinDev\PrestaShopModuleInstaller\ValueObject\ValueObjectInterface;

/**
 * @phpstan-type TIcon string|null
 * @phpstan-type TParamIcon TIcon
 */
final class Icon implements ValueObjectInterface
{
    /** @var TIcon */
    private $icon;

    /**
     * @param TParamIcon $icon
     */
    public function __construct($icon)
    {
        $this->ensureIsStringOrNull($icon);

        $icon = \is_null($icon)
            ? $icon
            : \trim($icon)
        ;

        $this->ensureIsStringValid($icon);

        $this->icon = $icon;
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->icon);
    }

    /**
     * {@inheritDoc}
     *
     * @return TIcon
     */
    public function getValue()
    {
        return $this->icon;
    }

    /**
     * @param TParamIcon $icon
     *
     * @return void
     *
     * @throws IconTypeIsInvalidException
     */
    private function ensureIsStringOrNull($icon)
    {
        if (null === $icon) {
            return;
        }

        if (true === \is_string($icon)) {
            return;
        }

        throw new IconTypeIsInvalidException('The Icon is not a string or null');
    }

    /**
     * @param TParamIcon $icon
     *
     * @return void
     *
     * @throws IconIsEmptyException
     */
    private function ensureIsStringValid($icon)
    {
        if (null === $icon) {
            return;
        }

        if (true === empty($icon)) {
            throw new IconIsEmptyException('The Icon is empty');
        }
    }
}
