<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Handler;

use Module;

abstract class AbstractHandlerInstaller implements HandlerInstallerInterface
{
    /** @var Module */
    private $module;

    public function __construct(Module $module)
    {
        $this->module = $module;
    }

    /**
     * @return Module
     */
    protected function getModule()
    {
        return $this->module;
    }
}
