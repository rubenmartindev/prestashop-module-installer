<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tab\Handler;

use Module;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\HandlerInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\Handler\Exception\FailedToCreateTabException;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\Handler\Exception\FailedToDeleteTabException;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\Handler\Exception\ParentTabNotFoundException;
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
 * @phpstan-type TItem array{
 *   class_name: TParamClassName,
 *   name: TParamName,
 *   parent_id?: TParamParentId,
 *   position?: TParamPosition,
 *   is_active?: TParamIsActive,
 *   is_enabled?: TParamIsEnabled,
 *   route_name?: TParamRouteName,
 *   icon?: TParamIcon,
 *   wording?: TParamWording,
 *   wording_domain?: TParamWordingDomain,
 * }
 */
interface TabHandlerInterface extends HandlerInterface
{
    /**
     * {@inheritDoc}
     *
     * @param TItem[] $items
     *
     * @return static
     */
    public static function createFrom(Module $module, array $items);

    /**
     * {@inheritDoc}
     *
     * @throws FailedToCreateTabException
     * @throws ParentTabNotFoundException
     */
    public function install();

    /**
     * {@inheritDoc}
     *
     * @throws FailedToDeleteTabException
     */
    public function uninstall();
}
