<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Configuration\Item;

use RubenMartinDev\PrestaShopModuleInstaller\Configuration\ValueObject\Name;
use RubenMartinDev\PrestaShopModuleInstaller\Configuration\ValueObject\Value;

/**
 * @phpstan-import-type TParamName from Name
 * @phpstan-import-type TParamPrefix from Name
 * @phpstan-import-type TParamValue from Value
 */
interface ConfigurationItemInterface
{
    /**
     * @param TParamPrefix $prefix
     * @param TParamName $name
     * @param callable|TParamValue $value
     *
     * @return static
     */
    public static function createFrom(
        $name,
        $value,
        $prefix = null
    );

    /**
     * @return Name
     */
    public function getName();

    /**
     * @return Value
     */
    public function getValue();
}
