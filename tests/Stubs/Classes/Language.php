<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Stubs\Classes;

class Language extends ObjectModel
{
    public static function getLanguages($active = true, $id_shop = false)
    {
        return [
            [
                'id_lang'   => 1,
                'iso_code'  => 'en',
            ],
            [
                'id_lang'   => 2,
                'iso_code'  => 'es',
            ],
        ];
    }
}
