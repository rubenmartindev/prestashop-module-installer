<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Configuration\ValueObject;

use RubenMartinDev\PrestaShopModuleInstaller\Configuration\ValueObject\Exception\NameIsEmptyException;
use RubenMartinDev\PrestaShopModuleInstaller\Configuration\ValueObject\Exception\NameIsNotValidException;
use RubenMartinDev\PrestaShopModuleInstaller\Configuration\ValueObject\Exception\NamePrefixTypeIsInvalidException;
use RubenMartinDev\PrestaShopModuleInstaller\Configuration\ValueObject\Exception\NameTypeIsInvalidException;
use RubenMartinDev\PrestaShopModuleInstaller\ValueObject\ValueObjectInterface;
use Validate;

/**
 * @phpstan-type TName string
 * @phpstan-type TPrefix string|null
 * @phpstan-type TParamName TName
 * @phpstan-type TParamPrefix TPrefix
 */
final class Name implements ValueObjectInterface
{
    /** @var TName */
    private $name;

    /** @var TPrefix */
    private $prefix;

    /**
     * @param TParamName $name
     * @param TParamPrefix $prefix
     */
    public function __construct($name, $prefix = null)
    {
        $this->ensureNameIsString($name);
        $this->ensurePrefixIsString($prefix);

        $name = \trim($name);
        $name = \strtoupper($name);

        if (true === \is_string($prefix)) {
            $prefix = \trim($prefix);
            $prefix = \strtoupper($prefix);
            $prefix = \rtrim($prefix, '_');
        }

        $this->ensureNameIsValid($name);

        $this->name     = $name;
        $this->prefix   = $prefix;
    }

    /**
     * {@inheritDoc}
     *
     * @return TName
     */
    public function getValue()
    {
        $value = "{$this->prefix}_{$this->name}";
        $value = \ltrim($value, '_');

        return $value;
    }

    /**
     * @return TName
     */
    public function getNameValue()
    {
        return $this->name;
    }

    /**
     * @return TPrefix
     */
    public function getPrefixValue()
    {
        return $this->prefix;
    }

    /**
     * @param TParamName $name
     *
     * @return void
     *
     * @throws NameTypeIsInvalidException
     */
    private function ensureNameIsString($name)
    {
        if (false === \is_string($name)) {
            throw new NameTypeIsInvalidException('The Name is not a string');
        }
    }

    /**
     * @param TParamPrefix $prefix
     *
     * @return void
     *
     * @throws NamePrefixTypeIsInvalidException
     */
    private function ensurePrefixIsString($prefix)
    {
        if (true === \is_null($prefix)) {
            return;
        }

        if (true === \is_string($prefix)) {
            return;
        }

        throw new NamePrefixTypeIsInvalidException('The Name prefix is not a string');
    }

    /**
     * @param TParamName $name
     *
     * @return void
     *
     * @throws NameIsEmptyException
     * @throws NameIsNotValidException
     */
    private function ensureNameIsValid($name)
    {
        if (true === empty($name)) {
            throw new NameIsEmptyException('The Name is empty');
        }

        if (false == Validate::isConfigName($name)) {
            throw new NameIsNotValidException('The Name is not valid');
        }
    }
}
