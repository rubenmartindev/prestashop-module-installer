<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Handler;

use Language;
use Module;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Exception\TabHandlerInstallerException;
use Tab;

/**
 * @phpstan-type TTabs array{
 *   name?: string|array<string, string>,
 *   class_name: string,
 *   parent?: int|string,
 *   position?: int,
 *   active?: bool,
 * }[]
 */
class TabHandlerInstaller extends AbstractHandlerInstaller
{
    const DEFAULT_TAB_PROPERTIES = [
        'parent'    => -1,
        'position'  => 0,
        'active'    => true,
        'module'    => null,
        'name'      => null,
    ];

    /** @var TTabs */
    protected $tabs;

    /**
     * @param Module $module
     * @param TTabs $tabs
     *
     * @throws TabHandlerInstallerException
     */
    public function __construct(Module $module, array $tabs)
    {
        parent::__construct($module);

        $this->tabs = \array_map(function (array $tabProperties) {
            if (!isset($tabProperties['class_name'])) {
                throw new TabHandlerInstallerException('The key class_name is required');
            }

            return \array_merge(
                self::DEFAULT_TAB_PROPERTIES,
                [
                    'module' => $this->getModule()->name,
                    'name'   => $tabProperties['class_name'],
                ],
                $tabProperties
            );
        }, $tabs);
    }

    /**
     * {@inheritDoc}
     *
     * @throws TabHandlerInstallerException
     */
    public function install()
    {
        foreach ($this->tabs as $tabProperties) {
            $tab = new Tab();

            $tab->class_name    = (string) $tabProperties['class_name'];
            $tab->position      = (int) $tabProperties['position'];
            $tab->active        = (bool) $tabProperties['active'];
            $tab->module        = (string) $tabProperties['module'];

            $tab->id_parent = \is_numeric($tabProperties['parent'])
                ? (int) $tabProperties['parent']
                : (int) Tab::getIdFromClassName($tabProperties['parent'])
            ;

            foreach (Language::getLanguages() as $language) {
                $tab->name[$language['id_lang']] = isset($tabProperties['name'][$language['iso_code']])
                    ? (string) $tabProperties['name'][$language['iso_code']]
                    : (string) $tabProperties['name']
                ;
            }

            if (!$tab->add()) {
                throw new TabHandlerInstallerException("Tab {$tab->class_name} not created");
            }
        }

        return true;
    }

    /**
     * {@inheritDoc}
     *
     * @throws TabHandlerInstallerException
     */
    public function uninstall()
    {
        /** @var Tab[] $tabs */
        $tabs = Tab::getCollectionFromModule($this->getModule()->name);

        foreach ($tabs as $tab) {
            if (!$tab->delete()) {
                throw new TabHandlerInstallerException("Tab {$tab->class_name} not deleted");
            }
        }

        return true;
    }
}
