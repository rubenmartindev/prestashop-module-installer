<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Hook\Item;

use Module;
use RubenMartinDev\PrestaShopModuleInstaller\Hook\ValueObject\Name;
use RubenMartinDev\PrestaShopModuleInstaller\Hook\ValueObject\PrestaShopVersion;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @phpstan-import-type TParamName from Name
 * @phpstan-import-type TParamPrestaShopVersion from PrestaShopVersion
 */
final class HookItem implements HookItemInterface
{
    const TYPE = 'hook';

    /** @var Name */
    private $name;

    /** @var PrestaShopVersion */
    private $prestashopVersion;

    public function __construct(
        Name $name,
        PrestaShopVersion $prestashopVersion
    ) {
        $this->name                 = $name;
        $this->prestashopVersion    = $prestashopVersion;
    }

    /**
     * {@inheritDoc}
     *
     * @param array{
     *   name: TParamName,
     *   prestashop_version?: TParamPrestaShopVersion,
     * } $properties
     */
    public static function createFrom(Module $module, array $properties)
    {
        $properties = self::createOptionsResolver($module)->resolve($properties);

        return new static(
            $properties['name'],
            $properties['prestashop_version']
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
    public function getPrestaShopVersion()
    {
        return $this->prestashopVersion;
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

        $resolver->setDefault('prestashop_version', null);

        $resolver->setAllowedTypes('name', 'string');
        $resolver->setAllowedTypes('prestashop_version', ['string', 'array', 'null']);

        $resolver->setNormalizer('name', function (Options $options, $value) {
            return new Name($value);
        });
        $resolver->setNormalizer('prestashop_version', function (Options $options, $value) {
            return new PrestaShopVersion($value);
        });

        return $resolver;
    }
}
