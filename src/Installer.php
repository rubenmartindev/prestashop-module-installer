<?php

namespace RubenMartinDev\PrestaShopModuleInstaller;

use Module;
use RubenMartinDev\PrestaShopModuleInstaller\Configuration\Handler\ConfigurationHandler;
use RubenMartinDev\PrestaShopModuleInstaller\Database\Handler\DatabaseHandler;
use RubenMartinDev\PrestaShopModuleInstaller\Exception\InstallerException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\HandlerInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Hook\Handler\HookHandler;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\Handler\TabHandler;

class Installer implements InstallerInterface
{
    /** @var array<string, HandlerInterface> */
    private static $mapHandlers = [
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
        $this->ensureIsCollectionHandlers($handlers);

        $this->handlers = $handlers;
    }

    /**
     * {@inheritDoc}
     */
    public static function createFrom(Module $module, array $handlers)
    {
        foreach ($handlers as $name => &$items) {
            /** @var HandlerInterface */
            $handlerClass = isset(self::$mapHandlers[$name]) ? self::$mapHandlers[$name] : $name;

            if (false === is_subclass_of($handlerClass, HandlerInterface::class)) {
                throw new InstallerException(\sprintf(
                    'The class %s does not implement the HandlerInterface',
                    $name
                ));
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
    public function addHandler(HandlerInterface $handler, $priority = null)
    {
        if (null === $priority) {
            $this->handlers[] = $handler;
        } else {
            \array_splice($this->handlers, $priority, 0, [$handler]);
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function removeHandler($prority)
    {
        if (isset($this->handlers[$prority])) {
            unset($this->handlers[$prority]);
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

    /**
     * @param array<HandlerInterface> $handlers
     *
     * @return void
     *
     * @throws InstallerException
     */
    private function ensureIsCollectionHandlers(array $handlers)
    {
        foreach ($handlers as $key => $handler) {
            if (false === \is_subclass_of($handler, HandlerInterface::class)) {
                throw new InstallerException(\sprintf(
                    'Handler "%s" does not implement the HandlerInterface',
                    $key
                ));
            }
        }
    }
}
