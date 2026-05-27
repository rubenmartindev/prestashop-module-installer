<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject;

use RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject\Exception\RouteNameIsEmptyException;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject\Exception\RouteNameTypeIsInvalidException;
use RubenMartinDev\PrestaShopModuleInstaller\ValueObject\ValueObjectInterface;

/**
 * @phpstan-type TRouteName string|null
 * @phpstan-type TParamRouteName TRouteName
 */
final class RouteName implements ValueObjectInterface
{
    /** @var TRouteName */
    private $routeName;

    /**
     * @param TParamRouteName $routeName
     */
    public function __construct($routeName)
    {
        $this->ensureIsStringOrNull($routeName);

        $routeName = \is_null($routeName)
            ? $routeName
            : \trim($routeName)
        ;

        $this->ensureIsStringValid($routeName);

        $this->routeName = $routeName;
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->routeName);
    }

    /**
     * {@inheritDoc}
     *
     * @return TRouteName
     */
    public function getValue()
    {
        return $this->routeName;
    }

    /**
     * @param TParamRouteName $routeName
     *
     * @return void
     *
     * @throws RouteNameTypeIsInvalidException
     */
    private function ensureIsStringOrNull($routeName)
    {
        if (null === $routeName) {
            return;
        }

        if (true === \is_string($routeName)) {
            return;
        }

        throw new RouteNameTypeIsInvalidException('The RouteName is not a string or null');
    }

    /**
     * @param TParamRouteName $routeName
     *
     * @return void
     *
     * @throws RouteNameIsEmptyException
     */
    private function ensureIsStringValid($routeName)
    {
        if (null === $routeName) {
            return;
        }

        if (true === empty($routeName)) {
            throw new RouteNameIsEmptyException('The RouteName is empty');
        }
    }
}
