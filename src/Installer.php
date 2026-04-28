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
        foreach ($handlers as $position => $handler) {
            $this->addHandler($position, $handler);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function addHandler($position, InstallerHandlerInterface $handler)
    {
        $this->handlers[$position] = $handler;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getHandler($position)
    {
        return isset($this->handlers[$position]) ? $this->handlers[$position] : null;
    }

    /**
     * {@inheritDoc}
     */
    public function removeHandler($position)
    {
        if (isset($this->handlers[$position])) {
            unset($this->handlers[$position]);
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
