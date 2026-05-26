<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Resources\Stubs\Classes;

/**
 * @see \Language
 */
class LanguageStub extends \ObjectModel
{
    const LANGUAGES = [
        1 => [
            'id_lang'   => 1,
            'iso_code'  => 'en',
        ],
        2 => [
            'id_lang'   => 2,
            'iso_code'  => 'es',
        ],
    ];

    /**
     * @see \Language::getLanguages()
     */
    public static function getLanguages($active = true, $id_shop = false)
    {
        return \array_values(self::LANGUAGES);
    }

    /**
     * @see \Language::getIsoById()
     */
    public static function getIsoById($id_lang)
    {
        return null !== self::LANGUAGES[$id_lang]['iso_code']
            ? self::LANGUAGES[$id_lang]['iso_code']
            : false
        ;
    }
}
