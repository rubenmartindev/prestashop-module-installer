<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject;

use RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject\Exception\WordingIsEmptyException;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject\Exception\WordingTypeIsInvalidException;
use RubenMartinDev\PrestaShopModuleInstaller\ValueObject\ValueObjectInterface;

/**
 * @phpstan-type TWording string|null
 * @phpstan-type TParamWording TWording
 */
final class Wording implements ValueObjectInterface
{
    /** @var TWording */
    private $wording;

    /**
     * @param TParamWording $wording
     */
    public function __construct($wording)
    {
        $this->ensureIsStringOrNull($wording);

        $wording = \is_null($wording)
            ? $wording
            : \trim($wording)
        ;

        $this->ensureIsStringValid($wording);

        $this->wording = $wording;
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->wording);
    }

    /**
     * {@inheritDoc}
     *
     * @return TWording
     */
    public function getValue()
    {
        return $this->wording;
    }

    /**
     * @param TParamWording $wording
     *
     * @return void
     *
     * @throws WordingTypeIsInvalidException
     */
    private function ensureIsStringOrNull($wording)
    {
        if (null === $wording) {
            return;
        }

        if (true === \is_string($wording)) {
            return;
        }

        throw new WordingTypeIsInvalidException('The Wording is not a string or null');
    }

    /**
     * @param TParamWording $wording
     *
     * @return void
     *
     * @throws WordingIsEmptyException
     */
    private function ensureIsStringValid($wording)
    {
        if (null === $wording) {
            return;
        }

        if (true === empty($wording)) {
            throw new WordingIsEmptyException('The Wording is empty');
        }
    }
}
