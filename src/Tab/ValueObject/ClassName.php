<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject;

use RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject\Exception\ClassNameIsEmptyException;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject\Exception\ClassNameTypeIsInvalidException;
use RubenMartinDev\PrestaShopModuleInstaller\ValueObject\ValueObjectInterface;

/**
 * @phpstan-type TClassName string
 * @phpstan-type TParamClassName TClassName
 */
final class ClassName implements ValueObjectInterface
{
    /** @var TClassName */
    private $className;

    /**
     * @param TParamClassName $className
     */
    public function __construct($className)
    {
        $this->ensureIsString($className);

        $className = \trim($className);

        $this->ensureIsNotEmpty($className);

        $this->className = $className;
    }

    /**
     * {@inheritDoc}
     *
     * @return TClassName
     */
    public function getValue()
    {
        return $this->className;
    }

    /**
     * @param TParamClassName $className
     *
     * @return void
     *
     * @throws ClassNameTypeIsInvalidException
     */
    private function ensureIsString($className)
    {
        if (false === \is_string($className)) {
            throw new ClassNameTypeIsInvalidException('The ClassName is not a string');
        }
    }

    /**
     * @param TParamClassName $className
     *
     * @return void
     *
     * @throws ClassNameIsEmptyException
     */
    private function ensureIsNotEmpty($className)
    {
        if (true === empty($className)) {
            throw new ClassNameIsEmptyException('The ClassName is empty');
        }
    }
}
