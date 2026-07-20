<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Configuration\Handler;

use Configuration as PrestaShopConfiguration;
use Module;
use RubenMartinDev\PrestaShopModuleInstaller\Configuration\Handler\Exception\FailedAddConfigurationException;
use RubenMartinDev\PrestaShopModuleInstaller\Configuration\Handler\Exception\FailedDeleteConfigurationException;
use RubenMartinDev\PrestaShopModuleInstaller\Configuration\Item\ConfigurationItem;
use RubenMartinDev\PrestaShopModuleInstaller\Configuration\Item\ConfigurationItemInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\AbstractHandler;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Exception\ItemTypeIsInvalidException;

/**
 * @method __construct(Module $module, ConfigurationItemInterface[] $items)
 */
final class ConfigurationHandler extends AbstractHandler implements ConfigurationHandlerInterface
{
    /**
     * {@inheritDoc}
     */
    public static function createFrom(Module $module, array $items)
    {
        $items = \array_map(
            function (array $item) use ($module) {
                return ConfigurationItem::createFrom($module, $item);
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
        /** @var ConfigurationItemInterface */
        foreach ($this->getItems() as $configuration) {
            if (false == PrestaShopConfiguration::updateValue(
                $configuration->getName()->getValue(),
                $configuration->getValue()->getValue()
            )) {
                throw new FailedAddConfigurationException(
                    \sprintf('Failed to add configuration "%s"', $configuration->getName()->getValue())
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
        /** @var ConfigurationItemInterface */
        foreach ($this->getItems() as $configuration) {
            if (false == PrestaShopConfiguration::deleteByName($configuration->getName()->getValue())) {
                throw new FailedDeleteConfigurationException(
                    \sprintf('Failed to delete configuration "%s"', $configuration->getName()->getValue())
                );
            }
        }

        return true;
    }

    /**
     * {@inheritDoc}
     */
    protected function ensureItemIsValid($item)
    {
        if (!$item instanceof ConfigurationItemInterface) {
            throw new ItemTypeIsInvalidException('The Item does not implement the ConfigurationItemInterface');
        }
    }
}
