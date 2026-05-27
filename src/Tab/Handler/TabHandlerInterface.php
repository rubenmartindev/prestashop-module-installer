<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tab\Handler;

use Module;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\HandlerInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\Handler\Exception\FailedToCreateTabException;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\Handler\Exception\FailedToDeleteTabException;
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
 *   className: TParamClassName,
 *   name: TParamName,
 *   parentId?: TParamParentId,
 *   position?: TParamPosition,
 *   active?: TParamIsActive,
 *   enabled?: TParamIsEnabled,
 *   routeName?: TParamRouteName,
 *   icon?: TParamIcon,
 *   wording?: TParamWording,
 *   wordingDomain?: TParamWordingDomain,
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
     */
    public function install();

    /**
     * {@inheritDoc}
     *
     * @throws FailedToDeleteTabException
     */
    public function uninstall();
}
