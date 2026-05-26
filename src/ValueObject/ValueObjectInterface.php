<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\ValueObject;

interface ValueObjectInterface
{
    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function isEquals($value);

    /**
     * @return mixed
     */
    public function getValue();
}
