<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tab\Item;

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

final class TabItem implements TabItemInterface
{
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
}
