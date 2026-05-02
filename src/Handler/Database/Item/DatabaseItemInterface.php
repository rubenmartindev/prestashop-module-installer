<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Handler\Database\Item;

interface DatabaseItemInterface
{
    /**
     * @return string
     */
    public function getTableName();

    /**
     * @return string
     */
    public function getQuery();

    /**
     * @return string
     */
    public function getQueryFile();

    /**
     * @return bool
     */
    public function getKeepData();
}
