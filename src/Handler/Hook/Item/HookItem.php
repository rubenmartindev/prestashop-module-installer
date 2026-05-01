<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\Item;

use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\Item\Exception\NameIsInvalidException;
use Validate;

class HookItem implements HookItemInterface
{
    /** @var string */
    private $name;

    /**
     * @param string $name
     *
     * @throws NameIsInvalidException
     */
    public function __construct($name)
    {
        if (!Validate::isHookName($name)) {
            throw new NameIsInvalidException(\sprintf('Invalid hook name "%s"', $name));
        }

        $this->name = $name;
    }

    /**
    * {@inheritDoc}
    */
    public static function create($hookName)
    {
        return new self($hookName);
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return $this->name;
    }
}
