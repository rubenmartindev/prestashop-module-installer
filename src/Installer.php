<?php

namespace RubenMartinDev\PrestaShopModuleInstaller;

use RubenMartinDev\PrestaShopModuleInstaller\Handler\InstallerHandlerInterface;

class Installer implements InstallerInterface
{
    /** @var array<int, InstallerHandlerInterface> */
    private $handlers = [];

    /**
     * @param iterable<int, InstallerHandlerInterface> $handlers
     */
    public function __construct($handlers = [])
    {
        foreach ($handlers as $priority => $handler) {
            $this->addHandler($priority, $handler);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function addHandler($priority, InstallerHandlerInterface $handler)
    {
        $this->handlers[$priority] = $handler;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getHandler($priority)
    {
        return isset($this->handlers[$priority]) ? $this->handlers[$priority] : null;
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
        dump(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function uninstall()
    {
        dump(__METHOD__);
    }
}
