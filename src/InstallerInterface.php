<?php

namespace RubenMartinDev\PrestaShopModuleInstaller;

use RubenMartinDev\PrestaShopModuleInstaller\Handler\InstallerHandlerInterface;

interface InstallerInterface
{
    /**
     * @param int $priority
     * @param InstallerHandlerInterface $handler
     *
     * @return static
     */
    public function addHandler($priority, InstallerHandlerInterface $handler);

    /**
     * @param int $priority
     *
     * @return InstallerHandlerInterface|null
     */
    public function getHandler($priority);

    /**
     * @param int $priority
     *
     * @return static
     */
    public function removeHandler($priority);

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
