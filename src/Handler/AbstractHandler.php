<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Handler;

use Module;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Exception\ItemsIsEmptyException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Exception\ItemTypeIsInvalidException;

abstract class AbstractHandler implements HandlerInterface
{
    /** @var mixed[] */
    private $items;

    /** @var Module */
    private $module;

    /**
     * @param Module $module
     * @param mixed[] $items
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

    /**
     * @return mixed[]
     */
    protected function getItems()
    {
        return $this->items;
    }
}
