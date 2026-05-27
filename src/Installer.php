<?php

namespace RubenMartinDev\PrestaShopModuleInstaller;

use RubenMartinDev\PrestaShopModuleInstaller\Handler\HandlerInterface;

class Installer implements InstallerInterface
{
    /** @var array<int, HandlerInterface> */
    private $handlers = [];

    /**
     * @param array<int, HandlerInterface> $handlers
     */
    public function __construct(array $handlers)
    {
        $this->handlers = $handlers;
    }

    /**
     * {@inheritDoc}
     */
    public function install()
    {
        foreach ($this->handlers as $handler) {
            $handler->install();
        }

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function uninstall()
    {
        foreach ($this->handlers as $handler) {
            $handler->uninstall();
        }

        return true;
    }
}
