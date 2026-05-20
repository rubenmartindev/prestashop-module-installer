<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\Item;

use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\Item\Exception\NameIsInvalidException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\Item\Exception\PrestaShopVersionIsInvalidException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\Item\Exception\PrestaShopVersionTypeIsInvalidException;
use RubenMartinDev\PrestaShopVersionChecker\PrestaShopVersionChecker;
use Validate;

/**
 * @phpstan-import-type TPrestaShopVersion from HookItemInterface as TPrestaShopVersionProperty
 *
 * @phpstan-type TPrestaShopVersionArray array{min: string, max?:string|null}|null
 * @phpstan-type TPrestaShopVersionParam string|TPrestaShopVersionArray|null
 */
class HookItem implements HookItemInterface
{
    /** @var string */
    private $name;

    /** @var TPrestaShopVersionProperty */
    private $prestashopVersion;

    /**
     * @param string $name
     * @param TPrestaShopVersionParam $prestashopVersion
     */
    public function __construct($name, $prestashopVersion = null)
    {
        $this->ensureNameIsValid($name);
        $this->ensurePrestaShopVersionIsValid($prestashopVersion);

        $this->name                 = $name;
        $this->prestashopVersion    = $this->parsePrestaShopVersion($prestashopVersion);
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function getPrestaShopVersion()
    {
        return $this->prestashopVersion;
    }

    /**
     * @param string $name
     *
     * @return void
     *
     * @throws NameIsInvalidException
     */
    private function ensureNameIsValid($name)
    {
        if (!Validate::isHookName($name)) {
            throw new NameIsInvalidException(\sprintf('Invalid hook name "%s"', $name));
        }
    }

    /**
     * @param TPrestaShopVersionParam $prestashopVersion
     *
     * @return void
     *
     * @throws PrestaShopVersionTypeIsInvalidException
     * @throws PrestaShopVersionIsInvalidException
     */
    private function ensurePrestaShopVersionIsValid($prestashopVersion)
    {
        if (!\is_null($prestashopVersion) && !\is_string($prestashopVersion) && !\is_array($prestashopVersion)) {
            throw new PrestaShopVersionTypeIsInvalidException('The $prestashopVersion is not a null, string or array');
        }

        if (null === $prestashopVersion) {
            return;
        }

        if (\is_string($prestashopVersion)) {
            if (false === PrestaShopVersionChecker::isCompareValid($prestashopVersion)) {
                throw new PrestaShopVersionIsInvalidException('The $prestashopVersion is not valid');
            }
        }

        if (\is_array($prestashopVersion)) {
            if (false === \array_key_exists('min', $prestashopVersion)) {
                throw new PrestaShopVersionIsInvalidException('The key $prestashopVersion["min"] not exists');
            }

            if (false === \is_null($prestashopVersion['min'])) {
                if (false === PrestaShopVersionChecker::isCompareValid($prestashopVersion['min'])) {
                    throw new PrestaShopVersionIsInvalidException('The $prestashopVersion["min"] is not valid');
                }
            }

            if (true === \array_key_exists('max', $prestashopVersion)) {
                if (false === \is_null($prestashopVersion['max'])) {
                    if (false === PrestaShopVersionChecker::isCompareValid($prestashopVersion['max'])) {
                        throw new PrestaShopVersionIsInvalidException('The $prestashopVersion["max"] is not valid');
                    }
                }
            }
        }
    }

    /**
     * @param TPrestaShopVersionParam $prestashopVersion
     *
     * @return TPrestaShopVersionProperty
     */
    private function parsePrestaShopVersion($prestashopVersion)
    {
        $parsedPrestashopVersion = [
            'min' => null,
            'max' => null,
        ];

        if (null === $prestashopVersion) {
            return $parsedPrestashopVersion;
        }

        if (true === \is_string($prestashopVersion)) {
            $prestashopVersion = ['min' => $prestashopVersion];
        }

        $parsedPrestashopVersion['min'] = $prestashopVersion['min'];

        if (true === \array_key_exists('max', $prestashopVersion)) {
            $parsedPrestashopVersion['max'] = $prestashopVersion['max'];
        }

        return $parsedPrestashopVersion;
    }
}
