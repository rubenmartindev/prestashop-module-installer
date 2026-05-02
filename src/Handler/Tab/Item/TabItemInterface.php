<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Item;

interface TabItemInterface
{
    /**
     * @return string
     */
    public function getClassName();

    /**
     * @return array<int, string>
     */
    public function getName();

    /**
     * @return int
     */
    public function getParentId();

    /**
     * @return int
     */
    public function getPosition();

    /**
     * @return bool
     */
    public function isActive();
}
