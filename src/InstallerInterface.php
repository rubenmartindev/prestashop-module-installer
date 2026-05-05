<?php

namespace RubenMartinDev\PrestaShopModuleInstaller;

use RubenMartinDev\PrestaShopModuleInstaller\Handler\HandlerInstallerInterface;

interface InstallerInterface
{
    /**
     * @param int $priority
     * @param HandlerInstallerInterface $handler
     *
     * @return static
     */
    public function addHandler($priority, HandlerInstallerInterface $handler);

    /**
     * @param int $priority
     *
     * @return HandlerInstallerInterface|null
     */
    public function getHandler($priority);

    /**
     * @param int $priority
     *
     * @return static
     */
    public function removeHandler($priority);

    /**
     * @return HandlerInstallerInterface[]
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
