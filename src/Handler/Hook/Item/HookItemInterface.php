<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Handler\Hook\Item;

interface HookItemInterface
{
    /**
     * @param string $hookName
     *
     * @return static
     */
    public static function create($hookName);

    /**
     * @return string
     */
    public function getName();
}
