<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook;

use Module;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\AbstractHandlerInstaller;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\Exception\FailedRegisterHookException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\Item\HookItemInterface;

class HookHandlerInstaller extends AbstractHandlerInstaller implements HookHandlerInstallerInterface
{
    /** @var HookItemInterface[] */
    private $hooks = [];

    /**
     * @param Module $module
     * @param HookItemInterface[] $hooks
     */
    public function __construct(Module $module, array $hooks = [])
    {
        parent::__construct($module);

        foreach ($hooks as $hook) {
            $this->addHook($hook);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function addHook(HookItemInterface $hookItem)
    {
        $this->hooks[$hookItem->getName()] = $hookItem;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getHook($hookName)
    {
        return isset($this->hooks[$hookName])
            ? $this->hooks[$hookName]
            : null
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function removeHook($hookName)
    {
        if (isset($this->hooks[$hookName])) {
            unset($this->hooks[$hookName]);
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getHooks()
    {
        return \array_values($this->hooks);
    }

    /**
     * {@inheritDoc}
     */
    public function install()
    {
        foreach ($this->hooks as $hook) {
            if (!$this->getModule()->registerHook($hook->getName())) {
                throw new FailedRegisterHookException(\sprintf('Failed to register hook "%s"', $hook->getName()));
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
