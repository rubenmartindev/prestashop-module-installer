<?php

namespace RubenMartinDev\PrestaShopModuleInstaller;

use RubenMartinDev\PrestaShopModuleInstaller\Handler\InstallerHandlerInterface;

interface InstallerInterface
{
    /**
     * @param int $position
     * @param InstallerHandlerInterface $handler
     *
     * @return static
     */
    public function addHandler($position, InstallerHandlerInterface $handler);

    /**
     * @param int $position
     *
     * @return InstallerHandlerInterface|null
     */
    public function getHandler($position);

    /**
     * @param int $position
     *
     * @return static
     */
    public function removeHandler($position);

    /**
     * @return InstallerHandlerInterface[]
     */
    public function getHandlers();

    /**
     * @return bool
     */
    public function install();

    /**
     * @return bool
     */
    public function uninstall();
}
