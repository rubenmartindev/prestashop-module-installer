<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook;

use Module;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\AbstractHandlerInstaller;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\Exception\FailedRegisterHookException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\Exception\HookHandlerInstallerException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\Exception\HooksIsEmptyException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\Exception\HooksMustBeInstanceOfHookItemException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\Item\HookItem;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\Item\HookItemInterface;

class HookHandlerInstaller extends AbstractHandlerInstaller implements HookHandlerInstallerInterface
{
    /** @var array<string, HookItemInterface> */
    private $hooks = [];

    /**
     * @param Module $module
     * @param HookItemInterface[] $hooks
     */
    public function __construct(Module $module, array $hooks)
    {
        parent::__construct($module);

        $this->ensureHooksIsValid($hooks);

        foreach ($hooks as $hook) {
            $this->addHook($hook);
        }
    }

    /**
     * {@inheritDoc}
     */
    public static function build(Module $module, array $hooks, $factory = null)
    {
        $factory = \is_callable($factory)
            ? $factory
            : function (array $hook) {
                if (!isset($hook['name'])) {
                    throw new HookHandlerInstallerException('The key name is required');
                }

                return new HookItem(
                    $hook['name']
                );
            }
        ;

        $hooks = \array_map($factory, $hooks);

        return new static($module, $hooks);
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
        return $this->hooks;
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

    /**
     * @param HookItemInterface[] $hooks
     *
     * @return void
     *
     * @throws HooksIsEmptyException
     * @throws HooksMustBeInstanceOfHookItemException
     */
    private function ensureHooksIsValid(array $hooks)
    {
        if (empty($hooks)) {
            throw new HooksIsEmptyException('The $hooks cannot be empty');
        }

        foreach ($hooks as $hook) {
            if (!$hook instanceof HookItemInterface) {
                throw new HooksMustBeInstanceOfHookItemException('The $hooks must be an array of HookItemInterface');
            }
        }
    }
}
