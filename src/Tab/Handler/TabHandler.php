<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tab\Handler;

use Db;
use DbQuery;
use Module;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\AbstractHandler;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\Handler\Exception\FailedToCreateTabException;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\Handler\Exception\FailedToDeleteTabException;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\Handler\Exception\ParentTabNotFoundException;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\Item\TabItem;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\Item\TabItemInterface;
use RubenMartinDev\PrestaShopModuleInstaller\Tab\ValueObject\ParentId;
use Tab;

/**
 * @method __construct(Module $module, TabItemInterface[] $items)
 */
final class TabHandler extends AbstractHandler implements TabHandlerInterface
{
    /**
     * {@inheritDoc}
     */
    public function install()
    {
        /** @var TabItemInterface */
        foreach ($this->getItems() as $item) {
            $prestashopTabId = (int) Tab::getIdFromClassName($item->getClassName()->getValue());

            $prestashopTab = new Tab();

            $prestashopTab->id              = $prestashopTabId;
            $prestashopTab->name            = $item->getName()->getValue();
            $prestashopTab->class_name      = $item->getClassName()->getValue();
            $prestashopTab->module          = $this->getModule()->name;
            $prestashopTab->id_parent       = $this->resolveParentId($item->getParentId());
            $prestashopTab->position        = $item->getPosition()->getValue();
            $prestashopTab->active          = $item->isActive()->getValue();
            $prestashopTab->enabled         = $item->isEnabled()->getValue();
            $prestashopTab->route_name      = $item->getRouteName()->getValue();
            $prestashopTab->icon            = $item->getIcon()->getValue();
            $prestashopTab->wording         = $item->getWording()->getValue();
            $prestashopTab->wording_domain  = $item->getWordingDomain()->getValue();

            if (!$prestashopTab->save()) {
                throw new FailedToCreateTabException("Tab {$item->getClassName()->getValue()} not created");
            }
        }

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function uninstall()
    {
        /** @var Tab[] $tabs */
        $tabs = Tab::getCollectionFromModule($this->getModule()->name);

        foreach ($tabs as $tab) {
            if (!$tab->delete()) {
                throw new FailedToDeleteTabException("Tab {$tab->class_name} not deleted");
            }
        }

        return true;
    }

    /**
     * {@inheritDoc}
     */
    protected static function getItemClassName()
    {
        return TabItem::class;
    }

    /**
     * @param ParentId $parentId
     *
     * @return int
     *
     * @throws ParentTabNotFoundException
     */
    private function resolveParentId(ParentId $parentId)
    {
        $parentIdValue = $parentId->getValue();

        if (\is_string($parentIdValue)) {
            $parentIdValue = Tab::getIdFromClassName($parentIdValue);
            $parentIdValue = false === $parentIdValue ? null : $parentIdValue;
        }

        if (\is_int($parentIdValue)) {
            if (!\in_array($parentIdValue, [ParentId::HIDDEN, ParentId::ROOT])) {
                $tabPrimary = Tab::$definition['primary'];
                $tabTable   = \_DB_PREFIX_ . Tab::$definition['table'];

                $parentIdValue = Db::getInstance()->getValue(
                    "SELECT `{$tabPrimary}` FROM {$tabTable} WHERE `{$tabPrimary}` = " . (int) $parentIdValue
                );
                $parentIdValue = false === $parentIdValue ? null : $parentIdValue;
            }
        }

        if (null === $parentIdValue) {
            throw new ParentTabNotFoundException("Parent tab {$parentId->getValue()} not found");
        }

        return $parentIdValue;
    }
}
