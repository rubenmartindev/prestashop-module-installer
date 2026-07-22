<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tab\Item;

use RubenMartinDev\PrestaShopModuleInstaller\Item\ItemInterface;
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

interface TabItemInterface extends ItemInterface
{
    /**
     * @return ClassName
     */
    public function getClassName();

    /**
     * @return Name
     */
    public function getName();

    /**
     * @return ParentId
     */
    public function getParentId();

    /**
     * @return Position
     */
    public function getPosition();

    /**
     * @return IsActive
     */
    public function isActive();

    /**
     * @return IsEnabled
     */
    public function isEnabled();

    /**
     * @return RouteName
     */
    public function getRouteName();

    /**
     * @return Icon
     */
    public function getIcon();

    /**
     * @return Wording
     */
    public function getWording();

    /**
     * @return WordingDomain
     */
    public function getWordingDomain();
}
