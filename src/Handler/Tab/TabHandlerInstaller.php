<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab;

use Module;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\AbstractHandlerInstaller;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Exception\FailedToCreateTabException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Exception\FailedToDeleteTabException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Exception\TabHandlerInstallerException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Exception\TabsIsEmptyException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Exception\TabsMustBeInstanceOfTabItemException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Item\TabItem;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Tab\Item\TabItemInterface;
use Tab;

class TabHandlerInstaller extends AbstractHandlerInstaller implements TabHandlerInstallerInterface
{
    /** @var array<string, TabItemInterface> */
    private $tabs = [];

    /**
     * @param Module $module
     * @param TabItemInterface[] $tabs
     */
    public function __construct(Module $module, array $tabs)
    {
        parent::__construct($module);

        $this->ensureTabsIsValid($tabs);

        foreach ($tabs as $tab) {
            $this->addTab($tab);
        }
    }

    /**
     * {@inheritDoc}
     */
    public static function build(Module $module, array $tabs, $factory = null)
    {
        $factory = \is_callable($factory)
            ? $factory
            : function (array $tab) {
                if (!isset($tab['className'])) {
                    throw new TabHandlerInstallerException('The key className is required');
                }

                if (!isset($tab['name'])) {
                    throw new TabHandlerInstallerException('The key name is required');
                }

                $tab['parentId']    = isset($tab['parentId']) ? $tab['parentId'] : -1;
                $tab['position']    = isset($tab['position']) ? $tab['position'] : 0;
                $tab['active']      = isset($tab['active']) ? $tab['active'] : true;

                return new TabItem(
                    $tab['className'],
                    $tab['name'],
                    $tab['parentId'],
                    $tab['position'],
                    $tab['active']
                );
            }
        ;

        $tabs = \array_map($factory, $tabs);

        return new static($module, $tabs);
    }

    /**
     * {@inheritDoc}
     */
    public function addTab(TabItemInterface $tab)
    {
        $this->tabs[$tab->getClassName()] = $tab;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getTab($className)
    {
        return isset($this->tabs[$className])
            ? $this->tabs[$className]
            : null
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function removeTab($className)
    {
        if (isset($this->tabs[$className])) {
            unset($this->tabs[$className]);
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getTabs()
    {
        return $this->tabs;
    }

    /**
     * {@inheritDoc}
     */
    public function install()
    {
        foreach ($this->tabs as $tab) {
            $prestashopTab = new Tab();

            $prestashopTab->name        = $tab->getName();
            $prestashopTab->class_name  = $tab->getClassName();
            $prestashopTab->module      = $this->getModule()->name;
            $prestashopTab->id_parent   = $tab->getParentId();
            $prestashopTab->position    = $tab->getPosition();
            $prestashopTab->active      = $tab->isActive();

            if (!$prestashopTab->add()) {
                throw new FailedToCreateTabException("Tab {$tab->getClassName()} not created");
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
     * @param TabItemInterface[] $tabs
     *
     * @return void
     *
     * @throws TabsIsEmptyException
     * @throws TabsMustBeInstanceOfTabItemException
     */
    private function ensureTabsIsValid(array $tabs)
    {
        if (empty($tabs)) {
            throw new TabsIsEmptyException('The $tabs cannot be empty');
        }

        foreach ($tabs as $tab) {
            if (!$tab instanceof TabItemInterface) {
                throw new TabsMustBeInstanceOfTabItemException('The $tabs must be an array of TabItemInterface');
            }
        }
    }
}
