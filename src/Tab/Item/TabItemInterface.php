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
interface TabItemInterface
{
    /**
     * @param TParamClassName $className
     * @param TParamName $name
     * @param TParamParentId $parentId
     * @param TParamPosition $position
     * @param TParamIsActive $isActive
     * @param TParamIsEnabled $isEnabled
     * @param TParamRouteName $routeName
     * @param TParamIcon $icon
     * @param TParamWording $wording
     * @param TParamWordingDomain $wordingDomain
     *
     * @return static
     */
    public static function createFrom(
        $className,
        $name,
        $parentId = -1,
        $position = 0,
        $isActive = true,
        $isEnabled = true,
        $routeName = null,
        $icon = null,
        $wording = null,
        $wordingDomain = null
    );

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
