<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Item;

use Language;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Item\Exception\ClassNameIsEmptyException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Item\Exception\ClassNameIsNotStringException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Item\Exception\NameIsEmptyException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Item\Exception\NameIsNotStringOrArrayException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Item\Exception\NameMissingLanguageIsoCodeEnException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Item\Exception\ParentIdIsEmptyException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Item\Exception\ParentIdIsNotStringOrArrayException;
use Tab;

class TabItem implements TabItemInterface
{
    /** @var string */
    private $className;

    /** @var array<string, string> */
    private $name;

    /** @var int|string */
    private $parentId;

    /** @var int */
    private $position;

    /** @var bool */
    private $active;

    /**
     * @param string $className
     * @param string|array<string, string> $name
     * @param int|string $parentId
     * @param int $position
     * @param bool $active
     */
    public function __construct(
        $className,
        $name,
        $parentId = -1,
        $position = 0,
        $active = true
    ) {
        $this->ensureClassNameIsValid($className);
        $this->ensureNameIsValid($name);
        $this->ensureParentIdIsValid($parentId);

        $this->className    = $className;
        $this->name         = $this->formattedName($name);
        $this->parentId     = $this->findParentId($parentId);
        $this->position     = (int) $position;
        $this->active       = (bool) $active;
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
     * @param string $className
     *
     * @return void
     *
     * @throws ClassNameIsNotStringException
     * @throws ClassNameIsEmptyException
     */
    private function ensureClassNameIsValid($className)
    {
        if (!\is_string($className)) {
            throw new ClassNameIsNotStringException('The $className is not a string');
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
     * @throws NameIsNotStringOrArrayException
     * @throws NameIsEmptyException
     */
    private function ensureNameIsValid($name)
    {
        if (!\is_string($name) && !\is_array($name)) {
            throw new NameIsNotStringOrArrayException('The $name is not a string or array');
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
     * @throws ParentIdIsNotStringOrArrayException
     * @throws ParentIdIsEmptyException
     */
    private function ensureParentIdIsValid($parentId)
    {
        if (!\is_numeric($parentId) && !\is_string($parentId)) {
            throw new ParentIdIsNotStringOrArrayException('The $parentId is not a string or numeric');
        }

        if (\is_string($parentId) && empty($parentId)) {
            throw new ParentIdIsEmptyException('The $parentId is empty');
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
