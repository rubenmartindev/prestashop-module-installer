<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab;

use RubenMartinDev\PrestaShopModuleInstaller\Handler\HandlerInstallerInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Exception\FailedToCreateTabException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Exception\FailedToDeleteTabException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Item\TabItemInterface;

interface TabHandlerInterface extends HandlerInstallerInterface
{
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
