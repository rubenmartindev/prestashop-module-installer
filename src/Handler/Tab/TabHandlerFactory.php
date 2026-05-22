<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab;

use Module;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Item\TabItem;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Item\TabItemInterface;

/**
 * @phpstan-type TTab array{
 *   className: string,
 *   name: string|array<string, string>,
 *   parentId?: int|string,
 *   position?: int,
 *   active?: bool,
 *   enabled?: bool,
 *   icon?: string|null,
 *   wording?: string|null,
 *   wordingDomain?: string|null,
 * }
 * @phpstan-type TTabs TTab[]
 */
class TabHandlerFactory
{
    /**
     * @param Module $module
     * @param TTabs $tabs
     * @param callable(TTab $tab): TabItemInterface|null $factory
     *
     * @return TabHandlerInterface
     */
    public static function create(
        Module $module,
        array $tabs,
        $factory = null
    ) {
        $factory = \is_callable($factory) ? $factory : [self::class, 'defaultFactory'];

        $tabs = \array_map($factory, $tabs);

        return new TabHandler($module, $tabs);
    }

    /**
     * @param TTab $tab
     *
     * @return TabItemInterface
     */
    private static function defaultFactory(array $tab)
    {
        $arguments = [
            isset($tab['className'])    ? $tab['className'] : '',
            isset($tab['name'])         ? $tab['name']      : '',
        ];

        if (isset($tab['parentId'])) {
            $arguments[] = $tab['parentId'];
        }

        if (isset($tab['position'])) {
            $arguments[] = $tab['position'];
        }

        if (isset($tab['active'])) {
            $arguments[] = $tab['active'];
        }

        if (isset($tab['enabled'])) {
            $arguments[] = $tab['enabled'];
        }

        if (\array_key_exists('icon', $tab)) {
            $arguments[] = $tab['icon'];
        }

        if (\array_key_exists('wording', $tab)) {
            $arguments[] = $tab['wording'];
        }

        if (\array_key_exists('wordingDomain', $tab)) {
            $arguments[] = $tab['wordingDomain'];
        }

        return new TabItem(...$arguments);
    }
}
