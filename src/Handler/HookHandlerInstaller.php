<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Handler;

use Module;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Exception\HookHandlerInstallerException;

class HookHandlerInstaller extends AbstractHandlerInstaller
{
    /** @var string[] */
    protected $hooks;

    /**
     * @param Module $module
     * @param string[] $hooks
     */
    public function __construct(Module $module, array $hooks)
    {
        parent::__construct($module);

        $this->hooks = $hooks;
    }

    /**
     * {@inheritDoc}
     */
    public function install()
    {
        foreach ($this->hooks as $hook) {
            if (!$this->getModule()->registerHook($hook)) {
                throw new HookHandlerInstallerException(\sprintf('Failed to register hook "%s"', $hook));
            }
        }

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function uninstall()
    {
        return true;
    }
}
