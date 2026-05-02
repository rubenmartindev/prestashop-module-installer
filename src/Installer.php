<?php

namespace RubenMartinDev\PrestaShopModuleInstaller;

use RubenMartinDev\PrestaShopModuleInstaller\Handler\HandlerInstallerInterface;

class Installer implements InstallerInterface
{
    /** @var array<int, HandlerInstallerInterface> */
    private $handlers = [];

    /**
     * @param array<int, HandlerInstallerInterface> $handlers
     */
    public function __construct(array $handlers)
    {
        foreach ($handlers as $priority => $handler) {
            $this->addHandler($priority, $handler);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function addHandler($priority, HandlerInstallerInterface $handler)
    {
        $this->handlers[$priority] = $handler;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getHandler($priority)
    {
        return isset($this->handlers[$priority])
            ? $this->handlers[$priority]
            : null
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function removeHandler($priority)
    {
        if (isset($this->handlers[$priority])) {
            unset($this->handlers[$priority]);
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getHandlers()
    {
        return $this->handlers;
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
