<?php

namespace RubenMartinDev\PrestaShopModuleInstaller;

use Module;
use RubenMartinDev\PrestaShopModuleInstaller\Configuration\Handler\ConfigurationHandler;
use RubenMartinDev\PrestaShopModuleInstaller\Database\Handler\DatabaseHandler;
use RubenMartinDev\PrestaShopModuleInstaller\Exception\InstallerException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\HandlerInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Hook\Handler\HookHandler;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\Handler\TabHandler;

/**
 * @phpstan-import-type THandlers from InstallerInterface
 */
class Installer implements InstallerInterface
{
    /** @var array<string, HandlerInterface> */
    const MAP_HANDLERS = [
        'configuration' => ConfigurationHandler::class,
        'database'      => DatabaseHandler::class,
        'hooks'         => HookHandler::class,
        'tabs'          => TabHandler::class,
    ];

    /** @var array<HandlerInterface> */
    private $handlers = [];

    /**
     * @param array<HandlerInterface> $handlers
     */
    public function __construct(array $handlers)
    {
        foreach ($handlers as $handler) {
            if (false === \is_subclass_of($handler, HandlerInterface::class)) {
                throw InstallerException::forInvalidHandler($handler);
            }
        }

        $this->handlers = $handlers;
    }

    /**
     * {@inheritDoc}
     *
     * @param THandlers $handlers
     */
    public static function createFrom(Module $module, array $handlers)
    {
        foreach ($handlers as $name => &$items) {
            /** @var HandlerInterface */
            $handlerClass = \array_key_exists($name, self::MAP_HANDLERS) ? self::MAP_HANDLERS[$name] : $name;

            if (false === \is_subclass_of($handlerClass, HandlerInterface::class)) {
                throw InstallerException::forInvalidHandler($handlerClass);
            }

            $items = $handlerClass::createFrom($module, $items);
        }

        $handlers = \array_values($handlers);

        return new static($handlers);
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
    public function addHandler(HandlerInterface $handler, $position = null)
    {
        if (null === $position) {
            $this->handlers[] = $handler;
        } elseif (\array_key_exists($position, $this->handlers)) {
            \array_splice($this->handlers, $position, 0, [$handler]);
        } else {
            $this->handlers[$position] = $handler;
        }

        return $this;
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
