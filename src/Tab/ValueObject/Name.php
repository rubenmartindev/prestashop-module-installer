<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject;

use Configuration;
use Language;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject\Exception\DefaultLanguageIdIsMissingInNameException;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject\Exception\KeyMustBeNumericInNameExpection;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject\Exception\NameIsEmptyException;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject\Exception\NameTypeIsInvalidException;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject\Exception\ValueIsEmptyInNameExpection;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject\Exception\ValueMustBeStringInNameExpection;
use RubenMartinDev\PrestaShopModuleInstaller\ValueObject\ValueObjectInterface;

/**
 * @phpstan-type TName string
 * @phpstan-type TArrayName array<int, TName>
 * @phpstan-type TParamName TName|TArrayName
 */
final class Name implements ValueObjectInterface
{
    /** @var TArrayName */
    private $name;

    /** @var int */
    private $defaultLanguageId;

    /**
     * @param TParamName $name
     */
    public function __construct($name)
    {
        $this->ensureIsStringOrArray($name);

        $this->defaultLanguageId = (int) Configuration::get('PS_LANG_DEFAULT');

        $this->ensureIsStringValid($name);
        $this->ensureIsArrayValid($name);

        $this->name = $this->formatter($name);
    }

    /**
     * @return TName
     */
    public function getDefaultLanguageValue()
    {
        return $this->name[$this->defaultLanguageId];
    }

    /**
     * {@inheritDoc}
     *
     * @return TArrayName
     */
    public function getValue()
    {
        return $this->name;
    }

    /**
     * @param int $languageId
     *
     * @return TName
     */
    public function getValueForLanguageId($languageId)
    {
        $languageId = (int) $languageId;

        return isset($this->name[$languageId])
            ? $this->name[$languageId]
            : $this->getDefaultLanguageValue()
        ;
    }

    /**
     * @param TParamName $name
     *
     * @return void
     *
     * @throws NameTypeIsInvalidException
     */
    private function ensureIsStringOrArray($name)
    {
        if (true === \is_string($name)) {
            return;
        }

        if (true === \is_array($name)) {
            return;
        }

        throw new NameTypeIsInvalidException('The Name is not a string or array');
    }

    /**
     * @param TParamName $name
     *
     * @return void
     *
     * @throws NameIsEmptyException
     */
    private function ensureIsStringValid($name)
    {
        if (false === \is_string($name)) {
            return;
        }

        $name = \trim($name);

        if (true === empty($name)) {
            throw new NameIsEmptyException('The Name is empty');
        }
    }

    /**
     * @param TParamName $name
     *
     * @return void
     *
     * @throws NameIsEmptyException
     * @throws KeyMustBeNumericInNameExpection
     * @throws ValueMustBeStringInNameExpection
     * @throws ValueIsEmptyInNameExpection
     * @throws DefaultLanguageIdIsMissingInNameException
     */
    private function ensureIsArrayValid($name)
    {
        if (false === \is_array($name)) {
            return;
        }

        if (true === empty($name)) {
            throw new NameIsEmptyException('The Name is empty');
        }

        foreach ($name as $key => $value) {
            if (false === \is_numeric($key)) {
                throw new KeyMustBeNumericInNameExpection('The key must be numeric in the Name');
            }

            if (false === \is_string($value)) {
                throw new ValueMustBeStringInNameExpection('The value must be string in the Name');
            }

            $value = \trim($value);

            if (true === empty($value)) {
                throw new ValueIsEmptyInNameExpection('The value is empty in the Name');
            }
        }

        if (false === isset($name[$this->defaultLanguageId])) {
            throw new DefaultLanguageIdIsMissingInNameException(\sprintf(
                'The "%s" key/value is missing in the Name',
                $this->defaultLanguageId
            ));
        }
    }

    /**
     * @param TParamName $name
     *
     * @return TArrayName
     */
    private function formatter($name)
    {
        if (true === \is_string($name)) {
            $name = [$this->defaultLanguageId => $name];
        }

        $formatted = [];

        foreach (Language::getLanguages() as $language) {
            $languageId = (int) $language['id_lang'];

            $formatted[$languageId] = isset($name[$languageId])
                ? $name[$languageId]
                : $name[$this->defaultLanguageId]
            ;

            $formatted[$languageId] = \trim($formatted[$languageId]);
        }

        return $formatted;
    }
}
