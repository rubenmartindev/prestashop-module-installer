<?php

namespace RubenMartinDev\PrestaShopModuleInstaller;

use Module;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Database\DatabaseHandlerInstaller;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\HandlerInstallerInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\HookHandlerInstaller;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\TabHandlerInstaller;

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
    public static function build(
        Module $module,
        array $handlers,
        $factoryDatabase = null,
        $factoryHooks = null,
        $factoryTabs = null
    ) {
        $factoryDatabase = \is_callable($factoryDatabase)
            ? $factoryDatabase
            : function (Module $module, array $properties) {
                return DatabaseHandlerInstaller::build($module, $properties);
            }
        ;
        $factoryHooks = \is_callable($factoryHooks)
            ? $factoryHooks
            : function (Module $module, array $properties) {
                return HookHandlerInstaller::build($module, $properties);
            }
        ;
        $factoryTabs = \is_callable($factoryTabs)
            ? $factoryTabs
            : function (Module $module, array $properties) {
                return TabHandlerInstaller::build($module, $properties);
            }
        ;

        foreach ($handlers as $name => &$properties) {
            if ('database' === $name) {
                $properties = $factoryDatabase($module, $properties);
            }

            if ('hooks' === $name) {
                $properties = $factoryHooks($module, $properties);
            }

            if ('tabs' === $name) {
                $properties = $factoryTabs($module, $properties);
            }
        }

        $handlers = \array_values($handlers);

        return new static($handlers);
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
