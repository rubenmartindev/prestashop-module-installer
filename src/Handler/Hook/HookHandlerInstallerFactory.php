<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook;

use Module;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\Exception\HookHandlerInstallerException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\Item\HookItem;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\Item\HookItemInterface;

/**
 * @phpstan-type THook array{
 *   name: string,
 * }
 *
 * @phpstan-type THooks THook[]
 */
class HookHandlerInstallerFactory
{
    /**
     * @param Module $module
     * @param THooks $hooks
     * @param callable(THook $hook): HookItemInterface|null $factory
     *
     * @return HookHandlerInstallerInterface
     */
    public static function create(Module $module, array $hooks, $factory = null)
    {
        $factory = \is_callable($factory)
            ? $factory
            : function (array $hook) {
                if (!isset($hook['name'])) {
                    throw new HookHandlerInstallerException('The key name is required');
                }

                return new HookItem(
                    $hook['name']
                );
            }
        ;

        $hooks = \array_map($factory, $hooks);

        return new HookHandlerInstaller($module, $hooks);
    }
}
