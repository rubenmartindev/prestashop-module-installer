<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Exception;

use PrestaShopException;

class InstallerException extends PrestaShopException
{
    /**
     * @param mixed $handler
     *
     * @return static
     */
    public static function forInvalidHandler($handler)
    {
        $type = \is_object($handler) ? \get_class($handler) : \gettype($handler);

        return new static(\sprintf(
            'The handler "%s" does not implement the HandlerInterface',
            $type
        ));
    }
}
