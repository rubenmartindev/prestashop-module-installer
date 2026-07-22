<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Configuration\Handler;

use Module;
use RubenMartinDev\PrestaShopModuleInstaller\Configuration\Handler\Exception\ConfigurationHandlerException;
use RubenMartinDev\PrestaShopModuleInstaller\Configuration\ValueObject\Name;
use RubenMartinDev\PrestaShopModuleInstaller\Configuration\ValueObject\Value;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\HandlerInterface;

/**
 * @phpstan-import-type TParamName from Name
 * @phpstan-import-type TParamValue from Value
 * @phpstan-import-type TParamPrefix from Name
 *
 * @phpstan-type TItem array{
 *   name: TParamName,
 *   value?: TParamValue,
 *   prefix?: TParamPrefix,
 * }
 */
interface ConfigurationHandlerInterface extends HandlerInterface
{
    /**
     * {@inheritDoc}
     *
     * @param TItem[] $items
     */
    public static function createFrom(Module $module, array $items);

    /**
     * {@inheritDoc}
     *
     * @throws ConfigurationHandlerException
     */
    public function install();

    /**
     * {@inheritDoc}
     *
     * @throws ConfigurationHandlerException
     */
    public function uninstall();
}
