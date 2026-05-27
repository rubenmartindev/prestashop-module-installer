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
 *
 * @phpstan-type TClassName TParamClassName
 * @phpstan-type TName TParamName
 * @phpstan-type TParentId TParamParentId
 * @phpstan-type TPosition TParamPosition
 * @phpstan-type TIsActive TParamIsActive
 * @phpstan-type TIsEnabled TParamIsEnabled
 * @phpstan-type TRouteName TParamRouteName
 * @phpstan-type TIcon TParamIcon
 * @phpstan-type TWording TParamWording
 * @phpstan-type TWordingDomain TParamWordingDomain
 */
final class TabItemFactory
{
    /**
     * @param TClassName $className
     * @param TName $name
     * @param TParentId $parentId
     * @param TPosition $position
     * @param TIsActive $isActive
     * @param TIsEnabled $isEnabled
     * @param TRouteName $routeName
     * @param TIcon $icon
     * @param TWording $wording
     * @param TWordingDomain $wordingDomain
     *
     * @return TabItemInterface
     */
    public static function create(
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
    ) {
        return new TabItem(
            new ClassName($className),
            new Name($name),
            new ParentId($parentId),
            new Position($position),
            new IsActive($isActive),
            new IsEnabled($isEnabled),
            new RouteName($routeName),
            new Icon($icon),
            new Wording($wording),
            new WordingDomain($wordingDomain)
        );
    }
}
