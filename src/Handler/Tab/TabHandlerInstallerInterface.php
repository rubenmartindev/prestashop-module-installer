<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab;

use Module;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\HandlerInstallerInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Exception\FailedToCreateTabException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Exception\FailedToDeleteTabException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Item\TabItemInterface;

/**
 * @phpstan-type TBuild array{
 *   className: string,
 *   name: string|array<string, string>,
 *   parentId?: int|string,
 *   position?: int,
 *   active?: bool,
 * }[]
 */
interface TabHandlerInstallerInterface extends HandlerInstallerInterface
{
    /**
     * @param Module $module
     * @param TBuild $tabs
     * @param callable|null $factory
     *
     * @return static
     */
    public static function build(Module $module, array $tabs, $factory = null);

    /**
     * @param TabItemInterface $tab
     *
     * @return static
     */
    public function addTab(TabItemInterface $tab);

    /**
     * @param string $className
     *
     * @return TabItemInterface|null
     */
    public function getTab($className);

    /**
     * @param string $className
     *
     * @return static
     */
    public function removeTab($className);

    /**
     * @return TabItemInterface[]
     */
    public function getTabs();

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
