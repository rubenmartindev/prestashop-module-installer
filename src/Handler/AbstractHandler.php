<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Handler;

use Module;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Exception\ItemsIsEmptyException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Exception\ItemTypeIsInvalidException;
use RubenMartinDev\PrestaShopModuleInstaller\Item\ItemInterface;

abstract class AbstractHandler implements HandlerInterface
{
    /** @var ItemInterface[] */
    private $items;

    /** @var Module */
    private $module;

    /**
     * @param Module $module
     * @param ItemInterface[] $items
     *
     * @throws ItemsIsEmptyException
     */
    public function __construct(Module $module, array $items)
    {
        $this->module = $module;

        if (empty($items)) {
            throw new ItemsIsEmptyException('The $items cannot be empty');
        }

        foreach ($items as $key => $item) {
            $this->ensureItemIsValid($item);

            $this->items[$key] = $item;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * {@inheritDoc}
     */
    public function addItem(ItemInterface $item, $position = null)
    {
        $this->ensureItemIsValid($item);

        if (null === $position) {
            $this->items[] = $item;
        } elseif (\array_key_exists($position, $this->items)) {
            \array_splice($this->items, $position, 0, [$item]);
        } else {
            $this->items[$position] = $item;
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function removeItem($position)
    {
        if (isset($this->items[$position])) {
            unset($this->items[$position]);
        }

        return $this;
    }

    /**
     * @param mixed $item
     *
     * @return void
     *
     * @throws ItemTypeIsInvalidException
     */
    abstract protected function ensureItemIsValid($item);

    /**
     * @return Module
     */
    protected function getModule()
    {
        return $this->module;
    }
}
