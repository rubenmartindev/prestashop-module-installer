<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Item;

use Configuration;
use Language;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Item\Exception\ClassNameIsEmptyException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Item\Exception\ClassNameTypeIsInvalidException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Item\Exception\IconIsEmptyException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Item\Exception\IconTypeIsInvalidException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Item\Exception\NameIsEmptyException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Item\Exception\NameMissingLanguageIsoCodeEnException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Item\Exception\NameTypeIsInvalidException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Item\Exception\ParentIdIsEmptyException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Item\Exception\ParentIdTypeIsInvalidException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Item\Exception\RouteNameIsEmptyException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Item\Exception\RouteNameTypeIsInvalidException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Item\Exception\WordingDomainIsEmptyException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Item\Exception\WordingDomainTypeIsInvalidException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Item\Exception\WordingIsEmptyException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Item\Exception\WordingTypeIsInvalidException;
use Tab;

class TabItem implements TabItemInterface
{
    /** @var string */
    private $className;

    /** @var array<int, string> */
    private $name;

    /** @var int|string */
    private $parentId;

    /** @var int */
    private $position;

    /** @var bool */
    private $active;

    /** @var bool */
    private $enabled;

    /** @var string|null */
    private $routeName;

    /** @var string|null */
    private $icon;

    /** @var string */
    private $wording;

    /** @var string */
    private $wordingDomain;

    /**
     * @param string $className
     * @param string|array<string, string> $name
     * @param int|string $parentId
     * @param int $position
     * @param bool $active
     * @param bool $enabled
     * @param string|null $routeName
     * @param string|null $icon
     * @param string|null $wording
     * @param string|null $wordingDomain
     */
    public function __construct(
        $className,
        $name,
        $parentId = -1,
        $position = 0,
        $active = true,
        $enabled = true,
        $routeName = null,
        $icon = null,
        $wording = null,
        $wordingDomain = null
    ) {
        $this->ensureClassNameIsValid($className);
        $this->ensureNameIsValid($name);
        $this->ensureParentIdIsValid($parentId);
        $this->ensureRouteNameIsValid($routeName);
        $this->ensureIconIsValid($icon);
        $this->ensureWordingIsValid($wording);
        $this->ensureWordingDomainIsValid($wordingDomain);

        $defaultLanguageId = (int) Configuration::get('PS_LANG_DEFAULT');

        $this->className        = $className;
        $this->name             = $this->formattedName($name);
        $this->parentId         = $this->findParentId($parentId);
        $this->position         = (int) $position;
        $this->active           = (bool) $active;
        $this->enabled          = (bool) $enabled;
        $this->routeName        = $routeName;
        $this->icon             = $icon;
        $this->wording          = null === $wording ? $this->name[$defaultLanguageId] : $wording;
        $this->wordingDomain    = null === $wordingDomain ? 'Admin.Navigation.Menu' : $wordingDomain;
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
     * @param string $className
     *
     * @return void
     *
     * @throws ClassNameTypeIsInvalidException
     * @throws ClassNameIsEmptyException
     */
    private function ensureClassNameIsValid($className)
    {
        if (!\is_string($className)) {
            throw new ClassNameTypeIsInvalidException('The $className is not a string');
        }

        if (empty($className)) {
            throw new ClassNameIsEmptyException('The $className is empty');
        }
    }

    /**
     * @param string|array|null $name
     *
     * @return void
     *
     * @throws NameTypeIsInvalidException
     * @throws NameIsEmptyException
     */
    private function ensureNameIsValid($name)
    {
        if (!\is_string($name) && !\is_array($name)) {
            throw new NameTypeIsInvalidException('The $name is not a string or array');
        }

        if (empty($name)) {
            throw new NameIsEmptyException('The $name is empty');
        }
    }

    /**
     * @param int|string $parentId
     *
     * @return void
     *
     * @throws ParentIdTypeIsInvalidException
     * @throws ParentIdIsEmptyException
     */
    private function ensureParentIdIsValid($parentId)
    {
        if (!\is_numeric($parentId) && !\is_string($parentId)) {
            throw new ParentIdTypeIsInvalidException('The $parentId is not a string or numeric');
        }

        if (\is_string($parentId) && empty($parentId)) {
            throw new ParentIdIsEmptyException('The $parentId is empty');
        }
    }

    /**
     * @param string|null $routeName
     *
     * @return void
     *
     * @throws RouteNameTypeIsInvalidException
     * @throws RouteNameIsEmptyException
     */
    private function ensureRouteNameIsValid($routeName)
    {
        if (null === $routeName) {
            return;
        }

        if (!\is_string($routeName)) {
            throw new RouteNameTypeIsInvalidException('The $routeName is not a string');
        }

        if (empty($routeName)) {
            throw new RouteNameIsEmptyException('The $routeName is empty');
        }
    }

    /**
     * @param string|null $icon
     *
     * @return void
     *
     * @throws IconTypeIsInvalidException
     * @throws IconIsEmptyException
     */
    private function ensureIconIsValid($icon)
    {
        if (null === $icon) {
            return;
        }

        if (!\is_string($icon)) {
            throw new IconTypeIsInvalidException('The $icon is not a string');
        }

        if (empty($icon)) {
            throw new IconIsEmptyException('The $icon is empty');
        }
    }

    /**
     * @param string|null $wording
     *
     * @return void
     *
     * @throws WordingTypeIsInvalidException
     * @throws WordingIsEmptyException
     */
    private function ensureWordingIsValid($wording)
    {
        if (null === $wording) {
            return;
        }

        if (!\is_string($wording)) {
            throw new WordingTypeIsInvalidException('The $wording is not a string');
        }

        if (empty($wording)) {
            throw new WordingIsEmptyException('The $wording is empty');
        }
    }

    /**
     * @param string|null $wordingDomain
     *
     * @return void
     *
     * @throws WordingDomainTypeIsInvalidException
     * @throws WordingDomainIsEmptyException
     */
    private function ensureWordingDomainIsValid($wordingDomain)
    {
        if (null === $wordingDomain) {
            return;
        }

        if (!\is_string($wordingDomain)) {
            throw new WordingDomainTypeIsInvalidException('The $wordingDomain is not a string');
        }

        if (empty($wordingDomain)) {
            throw new WordingDomainIsEmptyException('The $wordingDomain is empty');
        }
    }

    /**
     * @param string|array<string, string> $name
     *
     * @return array<int, string>
     *
     * @throws NameMissingLanguageIsoCodeEnException
     */
    private function formattedName($name)
    {
        if (false === \is_array($name)) {
            $name = ['en' => $name];
        }

        if (!isset($name['en'])) {
            throw new NameMissingLanguageIsoCodeEnException('The key `en` does not exist in the list of names');
        }

        $formattedName = [];

        foreach (Language::getLanguages() as $language) {
            $formattedName[$language['id_lang']] = $name['en'];

            if (isset($name[$language['iso_code']])) {
                $formattedName[$language['id_lang']] = $name[$language['iso_code']];
            }
        }

        return $formattedName;
    }

    /**
     * @param int|string $parentId
     *
     * @return int
     */
    private function findParentId($parentId)
    {
        $parentId = \is_numeric($parentId)
            ? (int) $parentId
            : (int) Tab::getIdFromClassName($parentId)
        ;

        return $parentId;
    }
}
