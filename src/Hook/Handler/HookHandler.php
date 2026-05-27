<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Hook\Handler;

use Module;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\AbstractHandler;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Exception\ItemTypeIsInvalidException;
use RubenMartinDev\PrestaShopModuleInstaller\Hook\Handler\Exception\FailedRegisterHookException;
use RubenMartinDev\PrestaShopModuleInstaller\Hook\Item\HookItem;
use RubenMartinDev\PrestaShopModuleInstaller\Hook\Item\HookItemInterface;
use RubenMartinDev\PrestaShopVersionChecker\PrestaShopVersionChecker;

/**
 * @method __construct(Module $module, HookItemInterface[] $items)
 */
final class HookHandler extends AbstractHandler implements HookHandlerInterface
{
    /**
     * {@inheritDoc}
     */
    public static function createFrom(Module $module, array $items)
    {
        $items = \array_map(
            function (array $item) {
                $defaultArguments = [
                    'name'              => '',
                    'prestashopVersion' => null,
                ];

                $arguments = \array_merge($defaultArguments, $item);
                $arguments = \array_values($arguments);

                return HookItem::createFrom(...$arguments);
            },
            $items
        );

        return new static($module, $items);
    }

    /**
     * {@inheritDoc}
     */
    public function install()
    {
        /** @var HookItemInterface */
        foreach ($this->getItems() as $hook) {
            if ($hook->getPrestaShopVersion()->getMinValue()) {
                if (false === PrestaShopVersionChecker::is($hook->getPrestaShopVersion()->getMinValue())) {
                    continue;
                }
            }

            if ($hook->getPrestaShopVersion()->getMaxValue()) {
                if (false === PrestaShopVersionChecker::is($hook->getPrestaShopVersion()->getMaxValue())) {
                    continue;
                }
            }

            if (!$this->getModule()->registerHook($hook->getName()->getValue())) {
                throw new FailedRegisterHookException(
                    \sprintf('Failed to register hook "%s"', $hook->getName()->getValue())
                );
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
     * {@inheritDoc}
     */
    protected function ensureItemIsValid($item)
    {
        if (!$item instanceof HookItemInterface) {
            throw new ItemTypeIsInvalidException('The Item does not implement the HookItemInterface');
        }
    }
}
