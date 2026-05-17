<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook;

use Module;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\Item\HookItem;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\Item\HookItemInterface;

/**
 * @phpstan-type THook array{
 *   name: string,
 *   prestashopVersion?: string|null,
 * }
 * @phpstan-type THooks THook[]
 */
class HookHandlerFactory
{
    /**
     * @param Module $module
     * @param THooks $hooks
     * @param callable(THook $hook): HookItemInterface|null $factory
     *
     * @return HookHandlerInterface
     */
    public static function create(
        Module $module,
        array $hooks,
        $factory = null
    ) {
        $factory = \is_callable($factory) ? $factory : [self::class, 'defaultFactory'];

        $hooks = \array_map($factory, $hooks);

        return new HookHandler($module, $hooks);
    }

    /**
     * @param THook $hook
     *
     * @return HookItemInterface
     */
    private static function defaultFactory(array $hook)
    {
        $arguments = [
            isset($hook['name'])                ? $hook['name']                 : '',
            isset($hook['prestashopVersion'])   ? $hook['prestashopVersion']    : null,
        ];

        return new HookItem(...$arguments);
    }
}
