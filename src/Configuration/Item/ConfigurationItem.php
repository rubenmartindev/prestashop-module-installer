<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Configuration\Item;

use Module;
use RubenMartinDev\PrestaShopModuleInstaller\Configuration\ValueObject\Name;
use RubenMartinDev\PrestaShopModuleInstaller\Configuration\ValueObject\Value;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @phpstan-import-type TParamName from Name
 * @phpstan-import-type TParamPrefix from Name
 * @phpstan-import-type TParamValue from Value
 */
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
     *
     * @param array{
     *   name: TParamName,
     *   value: callable|TParamValue,
     *   prefix?: TParamPrefix,
     * } $properties
     */
    public static function createFrom(Module $module, array $properties)
    {
        $properties = self::createOptionsResolver($module)->resolve($properties);

        return new static(
            $properties['name'],
            $properties['value']
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

    /**
     * @param Module $module
     *
     * @return OptionsResolver
     */
    private static function createOptionsResolver(Module $module)
    {
        $resolver = new OptionsResolver();

        $resolver->setRequired('name');

        $resolver->setDefault('value', null);
        $resolver->setDefault('prefix', $module->name);

        $resolver->setAllowedTypes('name', 'string');
        $resolver->setAllowedTypes('value', ['callable', 'bool', 'string', 'array', 'object', 'null']);
        $resolver->setAllowedTypes('prefix', ['string', 'null']);

        $resolver->setNormalizer('name', function (Options $options, $value) {
            return new Name($value, $options['prefix']);
        });
        $resolver->setNormalizer('value', function (Options $options, $value) {
            $value = \is_callable($value) ? $value() : $value;

            return new Value($value);
        });
        $resolver->setNormalizer('prefix', function (Options $options, $value) use ($module) {
            return $value === null ? $module->name : $value;
        });

        return $resolver;
    }
}
