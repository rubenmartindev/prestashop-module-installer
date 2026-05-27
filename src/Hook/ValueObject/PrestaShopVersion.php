<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Hook\ValueObject;

use RubenMartinDev\PrestaShopModuleInstaller\Hook\ValueObject\Exception\PrestaShopVersionIsInvalidException;
use RubenMartinDev\PrestaShopModuleInstaller\Hook\ValueObject\Exception\PrestaShopVersionTypeIsInvalidException;
use RubenMartinDev\PrestaShopModuleInstaller\ValueObject\ValueObjectInterface;
use RubenMartinDev\PrestaShopVersionChecker\PrestaShopVersionChecker;

/**
 * @phpstan-type TPrestaShopVersion string
 * @phpstan-type TOptionalPrestaShopVersion TPrestaShopVersion|null
 * @phpstan-type TArrayPrestaShopVersion array{min: TOptionalPrestaShopVersion, max: TOptionalPrestaShopVersion}
 * @phpstan-type TOptionalArrayPrestaShopVersion array{min: TOptionalPrestaShopVersion, max?: TOptionalPrestaShopVersion}
 * @phpstan-type TParamPrestaShopVersion TPrestaShopVersion|TOptionalArrayPrestaShopVersion|null
 */
final class PrestaShopVersion implements ValueObjectInterface
{
    /** @var TArrayPrestaShopVersion */
    private $prestashopVersion;

    /**
     * @param TParamPrestaShopVersion $prestashopVersion
     */
    public function __construct($prestashopVersion)
    {
        $this->ensureIsStringOrArrayOrNull($prestashopVersion);

        $this->ensureIsStringIsValid($prestashopVersion);
        $this->ensureIsArrayIsValid($prestashopVersion);

        $this->prestashopVersion = $this->formatter($prestashopVersion);
    }

    /**
     * {@inheritDoc}
     *
     * @return TArrayPrestaShopVersion
     */
    public function getValue()
    {
        return $this->prestashopVersion;
    }

    /**
     * @return TOptionalPrestaShopVersion
     */
    public function getMinValue()
    {
        return $this->prestashopVersion['min'];
    }

    /**
     * @return TOptionalPrestaShopVersion
     */
    public function getMaxValue()
    {
        return $this->prestashopVersion['max'];
    }

    /**
     * @param TParamPrestaShopVersion $prestashopVersion
     *
     * @return void
     *
     * @throws PrestaShopVersionTypeIsInvalidException
     */
    private function ensureIsStringOrArrayOrNull($prestashopVersion)
    {
        if (null === $prestashopVersion) {
            return;
        }

        if (true === \is_string($prestashopVersion)) {
            return;
        }

        if (true === \is_array($prestashopVersion)) {
            return;
        }

        throw new PrestaShopVersionTypeIsInvalidException('The PrestaShopVersion is not a string, array or null');
    }

    /**
     * @param TParamPrestaShopVersion $prestashopVersion
     *
     * @return void
     *
     * @throws PrestaShopVersionIsInvalidException
     */
    private function ensureIsStringIsValid($prestashopVersion)
    {
        if (false === \is_string($prestashopVersion)) {
            return;
        }

        if (false === PrestaShopVersionChecker::isCompareValid($prestashopVersion)) {
            throw new PrestaShopVersionIsInvalidException('The PrestaShopVersion is not valid');
        }
    }

    /**
     * @param TParamPrestaShopVersion $prestashopVersion
     *
     * @return void
     *
     * @throws PrestaShopVersionIsInvalidException
     */
    private function ensureIsArrayIsValid($prestashopVersion)
    {
        if (false === \is_array($prestashopVersion)) {
            return;
        }

        if (false === \array_key_exists('min', $prestashopVersion)) {
            throw new PrestaShopVersionIsInvalidException('The PrestaShopVersion key "min" not exists');
        }

        if (null !== $prestashopVersion['min']) {
            if (false === PrestaShopVersionChecker::isCompareValid($prestashopVersion['min'])) {
                throw new PrestaShopVersionIsInvalidException('The PrestaShopVersion key "min" is not valid');
            }
        }

        if (true === \array_key_exists('max', $prestashopVersion)) {
            if (null !== $prestashopVersion['max']) {
                if (false === PrestaShopVersionChecker::isCompareValid($prestashopVersion['max'])) {
                    throw new PrestaShopVersionIsInvalidException('The PrestaShopVersion key "max" is not valid');
                }
            }
        }
    }

    /**
     * @param TParamPrestaShopVersion $prestashopVersion
     *
     * @return TArrayPrestaShopVersion
     */
    private function formatter($prestashopVersion)
    {
        $formatted = [
            'min' => null,
            'max' => null,
        ];

        if (null === $prestashopVersion) {
            return $formatted;
        }

        if (true === \is_string($prestashopVersion)) {
            $prestashopVersion = ['min' => $prestashopVersion];
        }

        $formatted['min'] = $prestashopVersion['min'];

        if (true === \array_key_exists('max', $prestashopVersion)) {
            $formatted['max'] = $prestashopVersion['max'];
        }

        return $formatted;
    }
}
