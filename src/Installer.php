<?php

namespace RubenMartinDev\PrestaShopModuleInstaller;

use Module;
use RubenMartinDev\PrestaShopModuleInstaller\Database\Handler\DatabaseHandler;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\HandlerInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Hook\Handler\HookHandler;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\Handler\TabHandler;

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
    public static function createFrom(Module $module, array $handlers)
    {
        foreach ($handlers as $name => &$items) {
            switch (true) {
                case 'database' === $name:
                    $items = DatabaseHandler::createFrom($module, $items);
                    break;
                case 'hooks' === $name:
                    $items = HookHandler::createFrom($module, $items);
                    break;
                case 'tabs' === $name:
                    $items = TabHandler::createFrom($module, $items);
                    break;
                default:
                    $items = null;
                    break;
            }
        }

        $handlers = \array_filter($handlers);

        return new static($handlers);
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
