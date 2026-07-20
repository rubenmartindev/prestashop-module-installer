<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tab\Item;

use Module;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject\ClassName;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject\Icon;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject\IsActive;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject\IsEnabled;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject\Name;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject\ParentId;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject\Position;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject\RouteName;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject\Wording;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject\WordingDomain;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @phpstan-import-type TParamClassName from ClassName
 * @phpstan-import-type TParamName from Name
 * @phpstan-import-type TParamParentId from ParentId
 * @phpstan-import-type TParamPosition from Position
 * @phpstan-import-type TParamIsActive from IsActive
 * @phpstan-import-type TParamIsEnabled from IsEnabled
 * @phpstan-import-type TParamRouteName from RouteName
 * @phpstan-import-type TParamIcon from Icon
 * @phpstan-import-type TParamWording from Wording
 * @phpstan-import-type TParamWordingDomain from WordingDomain
 */
final class TabItem implements TabItemInterface
{
    const TYPE = 'tab';

    /** @var ClassName */
    private $className;

    /** @var Name */
    private $name;

    /** @var ParentId */
    private $parentId;

    /** @var Position */
    private $position;

    /** @var IsActive */
    private $active;

    /** @var IsEnabled */
    private $enabled;

    /** @var RouteName */
    private $routeName;

    /** @var Icon */
    private $icon;

    /** @var Wording */
    private $wording;

    /** @var WordingDomain */
    private $wordingDomain;

    public function __construct(
        ClassName $className,
        Name $name,
        ParentId $parentId,
        Position $position,
        IsActive $active,
        IsEnabled $enabled,
        RouteName $routeName,
        Icon $icon,
        Wording $wording,
        WordingDomain $wordingDomain
    ) {
        $this->className        = $className;
        $this->name             = $name;
        $this->parentId         = $parentId;
        $this->position         = $position;
        $this->active           = $active;
        $this->enabled          = $enabled;
        $this->routeName        = $routeName;
        $this->icon             = $icon;

        $this->wording          = $wording->isEmpty()
            ? new Wording($name->getDefaultLanguageValue())
            : $wording
        ;

        $this->wordingDomain    = $wordingDomain->isEmpty()
            ? new WordingDomain('Admin.Navigation.Menu')
            : $wordingDomain
        ;
    }

    /**
     * {@inheritDoc}
     *
     * @param array{
     *   class_name: TParamClassName,
     *   name: TParamName,
     *   parent_id?: TParamParentId,
     *   position?: TParamPosition,
     *   is_active?: TParamIsActive,
     *   is_enabled?: TParamIsEnabled,
     *   route_name?: TParamRouteName,
     *   icon?: TParamIcon,
     *   wording?: TParamWording,
     *   wording_domain?: TParamWordingDomain,
     * } $properties
     */
    public static function createFrom(Module $module, array $properties)
    {
        $properties = self::createOptionsResolver($module)->resolve($properties);

        return new static(
            $properties['class_name'],
            $properties['name'],
            $properties['parent_id'],
            $properties['position'],
            $properties['is_active'],
            $properties['is_enabled'],
            $properties['route_name'],
            $properties['icon'],
            $properties['wording'],
            $properties['wording_domain']
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
    public function getClassName()
    {
        return $this->className;
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
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * {@inheritDoc}
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * {@inheritDoc}
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * {@inheritDoc}
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * {@inheritDoc}
     */
    public function getRouteName()
    {
        return $this->routeName;
    }

    /**
     * {@inheritDoc}
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * {@inheritDoc}
     */
    public function getWording()
    {
        return $this->wording;
    }

    /**
     * {@inheritDoc}
     */
    public function getWordingDomain()
    {
        return $this->wordingDomain;
    }

    /**
     * @param Module $module
     *
     * @return OptionsResolver
     */
    private static function createOptionsResolver(Module $module)
    {
        $resolver = new OptionsResolver();

        $resolver->setRequired('class_name');
        $resolver->setRequired('name');

        $resolver->setDefault('parent_id', -1);
        $resolver->setDefault('position', 0);
        $resolver->setDefault('is_active', true);
        $resolver->setDefault('is_enabled', true);
        $resolver->setDefault('route_name', null);
        $resolver->setDefault('icon', null);
        $resolver->setDefault('wording', null);
        $resolver->setDefault('wording_domain', null);

        $resolver->setAllowedTypes('class_name', 'string');
        $resolver->setAllowedTypes('name', ['string', 'array']);
        $resolver->setAllowedTypes('parent_id', ['int', 'string']);
        $resolver->setAllowedTypes('position', 'int');
        $resolver->setAllowedTypes('is_active', 'bool');
        $resolver->setAllowedTypes('is_enabled', 'bool');
        $resolver->setAllowedTypes('route_name', ['string', 'null']);
        $resolver->setAllowedTypes('icon', ['string', 'null']);
        $resolver->setAllowedTypes('wording', ['string', 'null']);
        $resolver->setAllowedTypes('wording_domain', ['string', 'null']);

        $resolver->setNormalizer('class_name', function (Options $options, $value) {
            return new ClassName($value);
        });
        $resolver->setNormalizer('name', function (Options $options, $value) {
            return new Name($value);
        });
        $resolver->setNormalizer('parent_id', function (Options $options, $value) {
            return new ParentId($value);
        });
        $resolver->setNormalizer('position', function (Options $options, $value) {
            return new Position($value);
        });
        $resolver->setNormalizer('is_active', function (Options $options, $value) {
            return new IsActive($value);
        });
        $resolver->setNormalizer('is_enabled', function (Options $options, $value) {
            return new IsEnabled($value);
        });
        $resolver->setNormalizer('route_name', function (Options $options, $value) {
            return new RouteName($value);
        });
        $resolver->setNormalizer('icon', function (Options $options, $value) {
            return new Icon($value);
        });
        $resolver->setNormalizer('wording', function (Options $options, $value) {
            return new Wording($value);
        });
        $resolver->setNormalizer('wording_domain', function (Options $options, $value) {
            return new WordingDomain($value);
        });

        return $resolver;
    }
}
