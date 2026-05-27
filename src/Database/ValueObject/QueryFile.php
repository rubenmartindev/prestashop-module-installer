<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Database\ValueObject;

use RubenMartinDev\PrestaShopModuleInstaller\Database\ValueObject\Exception\QueryFileNotExistsException;
use RubenMartinDev\PrestaShopModuleInstaller\Database\ValueObject\Exception\QueryFileIsEmptyException;
use RubenMartinDev\PrestaShopModuleInstaller\Database\ValueObject\Exception\QueryFileIsNotRedeableException;
use RubenMartinDev\PrestaShopModuleInstaller\Database\ValueObject\Exception\QueryFileTypeIsInvalidException;
use RubenMartinDev\PrestaShopModuleInstaller\ValueObject\ValueObjectInterface;

final class QueryFile implements ValueObjectInterface
{
    /** @var string|null */
    private $queryFile = null;

    /**
     * @param string|null $queryFile
     */
    public function __construct($queryFile)
    {
        $this->ensureIsStringOrNull($queryFile);

        if (null === $queryFile) {
            return;
        }

        $queryFile = \trim($queryFile);

        $this->ensureIsNotEmpty($queryFile);
        $this->ensureFileIsRedeable($queryFile);

        $this->queryFile = $queryFile;
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->queryFile);
    }

    /**
     * {@inheritDoc}
     *
     * @return string|null
     */
    public function getValue()
    {
        return $this->queryFile;
    }

    /**
     * @param string|null $queryFile
     *
     * @return void
     *
     * @throws QueryFileTypeIsInvalidException
     */
    private function ensureIsStringOrNull($queryFile)
    {
        if (null === $queryFile) {
            return;
        }

        if (true === \is_string($queryFile)) {
            return;
        }

        throw new QueryFileTypeIsInvalidException('The QueryFile is not a string or null');
    }

    /**
     * @param string|null $queryFile
     *
     * @return void
     *
     * @throws QueryFileIsEmptyException
     */
    private function ensureIsNotEmpty($queryFile)
    {
        if (true === empty($queryFile)) {
            throw new QueryFileIsEmptyException('The QueryFile is empty');
        }
    }

    /**
     * @param string|null $queryFile
     *
     * @return void
     *
     * @throws QueryFileNotExistsException
     * @throws QueryFileIsNotRedeableException
     */
    private function ensureFileIsRedeable($queryFile)
    {
        if (false === \file_exists($queryFile)) {
            throw new QueryFileNotExistsException('The QueryFile does not exists');
        }

        if (false === \is_readable($queryFile)) {
            throw new QueryFileIsNotRedeableException('The QueryFile is not readable');
        }
    }
}
