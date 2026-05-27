<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tab\Handler;

use Module;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\Item\TabItemFactory;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\Item\TabItemInterface;

/**
 * @phpstan-import-type TClassName from TabItemFactory
 * @phpstan-import-type TName from TabItemFactory
 * @phpstan-import-type TParentId from TabItemFactory
 * @phpstan-import-type TPosition from TabItemFactory
 * @phpstan-import-type TIsActive from TabItemFactory
 * @phpstan-import-type TIsEnabled from TabItemFactory
 * @phpstan-import-type TRouteName from TabItemFactory
 * @phpstan-import-type TIcon from TabItemFactory
 * @phpstan-import-type TWording from TabItemFactory
 * @phpstan-import-type TWordingDomain from TabItemFactory
 *
 * @phpstan-type TItem array{
 *   className: TClassName,
 *   name: TName,
 *   parentId?: TParentId,
 *   position?: TPosition,
 *   active?: TIsActive,
 *   enabled?: TIsEnabled,
 *   routeName?: TRouteName,
 *   icon?: TIcon,
 *   wording?: TWording,
 *   wordingDomain?: TWordingDomain,
 * }
 * @phpstan-type TItems TItem[]
 */
class TabHandlerFactory
{
    /**
     * @param Module $module
     * @param TItems $items
     * @param callable(TItem $item): TabItemInterface|null $factory
     *
     * @return TabHandlerInterface
     */
    public static function create(
        Module $module,
        array $items,
        $factory = null
    ) {
        $factory = \is_callable($factory) ? $factory : [self::class, 'defaultFactory'];

        $items = \array_map($factory, $items);

        return new TabHandler($module, $items);
    }

    /**
     * @param TItem $item
     *
     * @return TabItemInterface
     */
    private static function defaultFactory(array $item)
    {
        $arguments = \array_values($item);

        return TabItemFactory::create(...$arguments);
    }
}
