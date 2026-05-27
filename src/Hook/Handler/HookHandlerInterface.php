<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Hook\Handler;

use Module;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\HandlerInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Hook\Handler\Exception\HookHandlerException;
use RubenMartinDev\PrestaShopModuleInstaller\Hook\ValueObject\Name;
use RubenMartinDev\PrestaShopModuleInstaller\Hook\ValueObject\PrestaShopVersion;

/**
 * @phpstan-import-type TParamName from Name
 * @phpstan-import-type TParamPrestaShopVersion from PrestaShopVersion
 *
 * @phpstan-type TItem array{
 *   name: TParamName,
 *   prestashopVersion?: TParamPrestaShopVersion,
 * }
 */
interface HookHandlerInterface extends HandlerInterface
{
    /**
     * {@inheritDoc}
     *
     * @param TItem[] $items
     *
     * @return static
     */
    public static function createFrom(Module $module, array $items);

    /**
     * {@inheritDoc}
     *
     * @throws HookHandlerException
     */
    public function install();

    /**
     * {@inheritDoc}
     *
     * @throws HookHandlerException
     */
    public function uninstall();
}
