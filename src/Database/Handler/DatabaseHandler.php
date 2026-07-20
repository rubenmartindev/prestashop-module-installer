<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Database\Handler;

use Db;
use Module;
use PrestaShopDatabaseException;
use RubenMartinDev\PrestaShopModuleInstaller\Database\Handler\Exception\FailedToExecuteQueryException;
use RubenMartinDev\PrestaShopModuleInstaller\Database\Item\DatabaseItem;
use RubenMartinDev\PrestaShopModuleInstaller\Database\Item\DatabaseItemInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\AbstractHandler;

/**
 * @method __construct(Module $module, DatabaseItemInterface[] $items)
 */
final class DatabaseHandler extends AbstractHandler implements DatabaseHandlerInterface
{
    /**
     * {@inheritDoc}
     */
    public function install()
    {
        /** @var DatabaseItemInterface $item */
        foreach ($this->getItems() as $item) {
            $this->executeSQL($item->getSQL());
        }

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function uninstall()
    {
        /** @var DatabaseItemInterface $item */
        foreach ($this->getItems() as $item) {
            if ($item->getKeepData()->getValue()) {
                continue;
            }

            $query = \sprintf('DROP TABLE IF EXISTS `%s`', _DB_PREFIX_ . $item->getTableName()->getValue());

            $this->executeSQL($query);
        }

        return true;
    }

    /**
     * @param string $query
     *
     * @return void
     *
     * @throws FailedToExecuteQueryException
     */
    protected function executeSQL($query)
    {
        try {
            Db::getInstance()->execute($query);
        } catch (PrestaShopDatabaseException $e) {
            throw new FailedToExecuteQueryException(
                \sprintf('An error occurred while executing the query: %s', $query)
            );
        }
    }

    /**
     * {@inheritDoc}
     */
    protected static function getItemClassName()
    {
        return DatabaseItem::class;
    }
}
