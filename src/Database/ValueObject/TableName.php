<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Database\ValueObject;

use RubenMartinDev\PrestaShopModuleInstaller\Database\ValueObject\Exception\TableNameIsEmptyException;
use RubenMartinDev\PrestaShopModuleInstaller\Database\ValueObject\Exception\TableNameIsNotValidException;
use RubenMartinDev\PrestaShopModuleInstaller\Database\ValueObject\Exception\TableNameTypeIsInvalidException;
use RubenMartinDev\PrestaShopModuleInstaller\ValueObject\ValueObjectInterface;
use Validate;

final class TableName implements ValueObjectInterface
{
    /** @var string */
    private $tableName;

    /**
     * @param string $tableName
     */
    public function __construct($tableName)
    {
        $this->ensureIsString($tableName);

        $tableName = \trim($tableName);

        $this->ensureIsValid($tableName);

        $this->tableName = $tableName;
    }

    /**
     * {@inheritDoc}
     */
    public function isEquals($value)
    {
        $value = $value instanceof ValueObjectInterface
            ? $value->getValue()
            : $value
        ;

        return $value === $this->tableName;
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getValue()
    {
        return $this->tableName;
    }

    /**
     * @param string $tableName
     *
     * @return void
     *
     * @throws TableNameTypeIsInvalidException
     */
    private function ensureIsString($tableName)
    {
        if (false === \is_string($tableName)) {
            throw new TableNameTypeIsInvalidException('The TableName is not a string');
        }
    }

    /**
     * @param string $tableName
     *
     * @return void
     *
     * @throws TableNameIsEmptyException
     * @throws TableNameIsNotValidException
     */
    private function ensureIsValid($tableName)
    {
        if (true === empty($tableName)) {
            throw new TableNameIsEmptyException('The TableName is empty');
        }

        if (false == Validate::isTableOrIdentifier($tableName)) {
            throw new TableNameIsNotValidException('The TableName is not valid');
        }
    }
}
