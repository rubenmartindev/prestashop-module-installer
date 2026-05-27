<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Hook\ValueObject;

use RubenMartinDev\PrestaShopModuleInstaller\Hook\ValueObject\Exception\NameIsEmptyException;
use RubenMartinDev\PrestaShopModuleInstaller\Hook\ValueObject\Exception\NameIsNotValidException;
use RubenMartinDev\PrestaShopModuleInstaller\Hook\ValueObject\Exception\NameTypeIsInvalidException;
use RubenMartinDev\PrestaShopModuleInstaller\ValueObject\ValueObjectInterface;
use Validate;

/**
 * @phpstan-type TName string
 * @phpstan-type TParamName TName
 */
final class Name implements ValueObjectInterface
{
    /** @var TName */
    private $name;

    /**
     * @param TParamName $name
     */
    public function __construct($name)
    {
        $this->ensureIsString($name);

        $name = \trim($name);

        $this->ensureIsValid($name);

        $this->name = $name;
    }

    /**
     * {@inheritDoc}
     *
     * @return TName
     */
    public function getValue()
    {
        return $this->name;
    }

    /**
     * @param TParamName $name
     *
     * @return void
     *
     * @throws NameTypeIsInvalidException
     */
    private function ensureIsString($name)
    {
        if (false === \is_string($name)) {
            throw new NameTypeIsInvalidException('The Name is not a string');
        }
    }

    /**
     * @param TParamName $name
     *
     * @return void
     *
     * @throws NameIsEmptyException
     * @throws NameIsNotValidException
     */
    private function ensureIsValid($name)
    {
        if (true === empty($name)) {
            throw new NameIsEmptyException('The Name is empty');
        }

        if (false == Validate::isHookName($name)) {
            throw new NameIsNotValidException('The Name is not valid');
        }
    }
}
