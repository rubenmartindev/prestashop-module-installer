<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Unit\Handler;

use RubenMartinDev\PrestaShopModuleInstaller\Handler\Exception\TabHandlerInstallerException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\TabHandlerInstaller;
use RubenMartinDev\PrestaShopModuleInstaller\Tests\Stubs\Classes\Tab;

class TabHandlerInstallerTest extends AbstractHandlerInstallerTestCase
{
    public function testInstallReturnsTrueWhenWithoutTabs()
    {
        $handler = new TabHandlerInstaller($this->module, []);

        $this->assertTrue($handler->install());
    }

    public function testInstallReturnsTrueWhenWithTabs()
    {
        $handler = new TabHandlerInstaller($this->module, [
            [
                'class_name'    => 'AdminMyModule1',
                'name'          => 'My tab 1',
            ],
            [
                'class_name'    => 'AdminMyModule2',
                'parent'        => 'AdminParentTab',
                'name'          => 'My tab 2',
            ],
        ]);

        $this->assertTrue($handler->install());
    }

    public function testInstallThrowsExceptionWhenWithoutClassName()
    {
        $this->expectException(TabHandlerInstallerException::class);

        $handler = new TabHandlerInstaller($this->module, [
            [
                'name' => 'My tab',
            ],
        ]);

        $handler->install();
    }

    /**
     * @runInSeparateProcess
     */
    public function testInstallThrowsExceptionWhenCreatingTabFails()
    {
        $this->expectException(TabHandlerInstallerException::class);

        Tab::$forceReturnFalseOnAdd = true;

        $handler = new TabHandlerInstaller($this->module, [
            [
                'class_name' => 'AdminMyModule',
            ],
        ]);

        $handler->install();
    }

    public function testUninstallReturnsTrue()
    {
        $handler = new TabHandlerInstaller($this->module, []);

        $this->assertTrue($handler->uninstall());
    }

    /**
     * @runInSeparateProcess
     */
    public function testUninstallThrowsExceptionWhenRemovingTabFails()
    {
        $this->expectException(TabHandlerInstallerException::class);

        Tab::$forceReturnFalseOnDelete = true;

        $handler = new TabHandlerInstaller($this->module, []);

        $handler->uninstall();
    }
}
