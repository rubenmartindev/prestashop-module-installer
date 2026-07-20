<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Configuration\Handler;

use Configuration as PrestaShopConfiguration;
use Module;
use RubenMartinDev\PrestaShopModuleInstaller\Configuration\Handler\Exception\FailedAddConfigurationException;
use RubenMartinDev\PrestaShopModuleInstaller\Configuration\Handler\Exception\FailedDeleteConfigurationException;
use RubenMartinDev\PrestaShopModuleInstaller\Configuration\Item\ConfigurationItem;
use RubenMartinDev\PrestaShopModuleInstaller\Configuration\Item\ConfigurationItemInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\AbstractHandler;

/**
 * @method __construct(Module $module, ConfigurationItemInterface[] $items)
 */
final class ConfigurationHandler extends AbstractHandler implements ConfigurationHandlerInterface
{
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
    protected static function getItemClassName()
    {
        return ConfigurationItem::class;
    }
}
