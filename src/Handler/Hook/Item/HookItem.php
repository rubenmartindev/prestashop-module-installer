<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\Item;

use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\Item\Exception\NameIsInvalidException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\Item\Exception\PrestaShopVersionIsInvalidException;
use RubenMartinDev\PrestaShopVersionChecker\PrestaShopVersionChecker;
use Validate;

class HookItem implements HookItemInterface
{
    /** @var string */
    private $name;

    /** @var string|null */
    private $prestashopVersion;

    /**
     * @param string $name
     * @param string|null $prestashopVersion
     *
     * @throws NameIsInvalidException
     * @throws PrestaShopVersionIsInvalidException
     */
    public function __construct($name, $prestashopVersion = null)
    {
        $this->ensureNameIsValid($name);
        $this->ensurePrestaShopVersionIsValid($prestashopVersion);

        $this->name                 = $name;
        $this->prestashopVersion    = $prestashopVersion;
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
     * @param string|null $prestashopVersion
     *
     * @return void
     *
     * @throws PrestaShopVersionIsInvalidException
     * @throws PrestaShopVersionIsEmptyException
     */
    private function ensurePrestaShopVersionIsValid($prestashopVersion)
    {
        if (null === $prestashopVersion) {
            return;
        }

        if (false === PrestaShopVersionChecker::isCompareValid($prestashopVersion)) {
            throw new PrestaShopVersionIsInvalidException('The $prestashopVersion is not valid');
        }
    }
}
