<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Configuration\Item;

use RubenMartinDev\PrestaShopModuleInstaller\Configuration\ValueObject\Name;
use RubenMartinDev\PrestaShopModuleInstaller\Configuration\ValueObject\Value;

final class ConfigurationItem implements ConfigurationItemInterface
{
    const TYPE = 'configuration';

    /** @var Name */
    private $name;

    /** @var Value */
    private $value;

    public function __construct(
        Name $name,
        Value $value
    ) {
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * {@inheritDoc}
     */
    public static function createFrom(
        $name,
        $value,
        $prefix = null
    ) {
        $value = \is_callable($value) ? $value() : $value;

        return new static(
            new Name($name, $prefix),
            new Value($value)
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getType()
    {
        return self::TYPE;
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
    public function getValue()
    {
        return $this->value;
    }
}
