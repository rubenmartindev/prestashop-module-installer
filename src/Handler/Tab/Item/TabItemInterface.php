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

    /**
     * @return bool
     */
    public function isEnabled();

    /**
     * @return string|null
     */
    public function getRouteName();

    /**
     * @return string|null
     */
    public function getIcon();

    /**
     * @return string|null
     */
    public function getWording();

    /**
     * @return string|null
     */
    public function getWordingDomain();
}
