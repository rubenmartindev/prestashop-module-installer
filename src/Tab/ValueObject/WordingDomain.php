<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject;

use RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject\Exception\WordingDomainIsEmptyException;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject\Exception\WordingDomainTypeIsInvalidException;
use RubenMartinDev\PrestaShopModuleInstaller\ValueObject\ValueObjectInterface;

/**
 * @phpstan-type TWordingDomain string|null
 * @phpstan-type TParamWordingDomain TWordingDomain
 */
final class WordingDomain implements ValueObjectInterface
{
    /** @var TWordingDomain */
    private $wordingDomain;

    /**
     * @param TParamWordingDomain $wordingDomain
     */
    public function __construct($wordingDomain)
    {
        $this->ensureIsStringOrNull($wordingDomain);

        $wordingDomain = \is_null($wordingDomain)
            ? $wordingDomain
            : \trim($wordingDomain)
        ;

        $this->ensureIsStringValid($wordingDomain);

        $this->wordingDomain = $wordingDomain;
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->wordingDomain);
    }

    /**
     * {@inheritDoc}
     *
     * @return TWordingDomain
     */
    public function getValue()
    {
        return $this->wordingDomain;
    }

    /**
     * @param TParamWordingDomain $wordingDomain
     *
     * @return void
     *
     * @throws WordingDomainTypeIsInvalidException
     */
    private function ensureIsStringOrNull($wordingDomain)
    {
        if (null === $wordingDomain) {
            return;
        }

        if (true === \is_string($wordingDomain)) {
            return;
        }

        throw new WordingDomainTypeIsInvalidException('The WordingDomain is not a string or null');
    }

    /**
     * @param TParamWordingDomain $wordingDomain
     *
     * @return void
     *
     * @throws WordingDomainIsEmptyException
     */
    private function ensureIsStringValid($wordingDomain)
    {
        if (null === $wordingDomain) {
            return;
        }

        if (true === empty($wordingDomain)) {
            throw new WordingDomainIsEmptyException('The WordingDomain is empty');
        }
    }
}
