<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab;

use Module;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Exception\TabHandlerException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Item\TabItem;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Item\TabItemInterface;

/**
 * @phpstan-type TTab array{
 *   className: string,
 *   name: string|array<string, string>,
 *   parentId?: int|string,
 *   position?: int,
 *   active?: bool,
 * }
 *
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
    public static function create(Module $module, array $tabs, $factory = null)
    {
        $factory = \is_callable($factory)
            ? $factory
            : function (array $tab) {
                if (!isset($tab['className'])) {
                    throw new TabHandlerException('The key className is required');
                }

                if (!isset($tab['name'])) {
                    throw new TabHandlerException('The key name is required');
                }

                $tab['parentId']    = isset($tab['parentId']) ? $tab['parentId'] : -1;
                $tab['position']    = isset($tab['position']) ? $tab['position'] : 0;
                $tab['active']      = isset($tab['active']) ? $tab['active'] : true;

                return new TabItem(
                    $tab['className'],
                    $tab['name'],
                    $tab['parentId'],
                    $tab['position'],
                    $tab['active']
                );
            }
        ;

        $tabs = \array_map($factory, $tabs);

        return new TabHandler($module, $tabs);
    }
}
