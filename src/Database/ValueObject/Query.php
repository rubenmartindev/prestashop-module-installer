<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Database\ValueObject;

use RubenMartinDev\PrestaShopModuleInstaller\Database\ValueObject\Exception\QueryIsEmptyException;
use RubenMartinDev\PrestaShopModuleInstaller\Database\ValueObject\Exception\QueryTypeIsInvalidException;
use RubenMartinDev\PrestaShopModuleInstaller\ValueObject\ValueObjectInterface;

/**
 * @phpstan-type TQuery string|null
 * @phpstan-type TParamQuery TQuery
 */
final class Query implements ValueObjectInterface
{
    /** @var TQuery */
    private $query = null;

    /**
     * @param TParamQuery $query
     */
    public function __construct($query)
    {
        $this->ensureIsStringOrNull($query);

        if (null === $query) {
            return;
        }

        $query = \trim($query);

        $this->ensureIsNotEmpty($query);

        $this->query = $query;
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->query);
    }

    /**
     * {@inheritDoc}
     *
     * @return TQuery
     */
    public function getValue()
    {
        return $this->query;
    }

    /**
     * @param TParamQuery $query
     *
     * @return void
     *
     * @throws QueryTypeIsInvalidException
     */
    private function ensureIsStringOrNull($query)
    {
        if (null === $query) {
            return;
        }

        if (false === \is_string($query)) {
            throw new QueryTypeIsInvalidException('The Query is not a string or null');
        }
    }

    /**
     * @param TParamQuery $query
     *
     * @return void
     *
     * @throws QueryIsEmptyException
     */
    private function ensureIsNotEmpty($query)
    {
        if (true === empty($query)) {
            throw new QueryIsEmptyException('The Query is empty');
        }
    }
}
