<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Hook\Handler;

use Module;
use RubenMartinDev\PrestaShopModuleInstaller\Hook\Handler\HookHandlerInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Hook\Item\HookItemFactory;
use RubenMartinDev\PrestaShopModuleInstaller\Hook\Item\HookItemInterface;

/**
 * @phpstan-import-type TName from HookItemFactory
 * @phpstan-import-type TPrestaShopVersion from HookItemFactory
 *
 * @phpstan-type TItem array{
 *   name: TName,
 *   prestashopVersion?: TPrestaShopVersion,
 * }
 * @phpstan-type TItems TItem[]
 */
final class HookHandlerFactory
{
    /**
     * @param Module $module
     * @param TItems $items
     * @param callable(TItem $item): HookItemInterface|null $factory
     *
     * @return HookHandlerInterface
     */
    public static function create(
        Module $module,
        array $items,
        $factory = null
    ) {
        $factory = \is_callable($factory) ? $factory : [self::class, 'defaultFactory'];

        $items = \array_map($factory, $items);

        return new HookHandler($module, $items);
    }

    /**
     * @param TItem $item
     *
     * @return HookItemInterface
     */
    private static function defaultFactory(array $item)
    {
        $arguments = \array_values($item);

        return HookItemFactory::create(...$arguments);
    }
}
