---
name: prestashop-module-installer
description: Use RubenMartinDev/PrestaShopModuleInstaller to implement or modify a PrestaShop module's installation and uninstallation lifecycle, compose configuration, database, hook, and tab handlers, diagnose installer failures, or create a custom handler. Includes detailed reference guidance for every built-in handler.
---

# PrestaShop Module Installer

Use the library's declarative factory unless an existing module already constructs handlers directly.

## Build the installer

Import `RubenMartinDev\PrestaShopModuleInstaller\Installer` and pass the module plus an ordered map of handlers to `Installer::createFrom()`:

```php
private function getInstaller()
{
    return Installer::createFrom($this, [
        'configuration' => [
            ['name' => 'is_enabled', 'value' => true],
        ],
        'database' => [
            [
                'table_name' => 'my_table',
                'query' => 'CREATE TABLE `{{DB_PREFIX}}my_table`;',
            ],
        ],
        'hooks' => [
            ['name' => 'displayHeader'],
        ],
        'tabs' => [
            ['class_name' => 'AdminMyTab', 'name' => 'My admin tab'],
        ],
    ]);
}
```

Use only these built-in keys: `configuration`, `database`, `hooks`, and `tabs`. Omit a built-in key when it has no items; each built-in handler raises `ItemsIsEmptyException` for an empty item list. A custom handler defines its own empty-list behavior.

## Integrate the lifecycle

Call the parent PrestaShop lifecycle method before running the installer:

```php
public function install()
{
    if (!parent::install()) {
        return false;
    }

    try {
        return $this->getInstaller()->install();
    } catch (InstallerException $e) {
        $this->_errors[] = $e->getMessage();
    }

    return false;
}

public function uninstall()
{
    if (!parent::uninstall()) {
        return false;
    }

    try {
        return $this->getInstaller()->uninstall();
    } catch (InstallerException $e) {
        $this->_errors[] = $e->getMessage();
    }

    return false;
}
```

Import `RubenMartinDev\PrestaShopModuleInstaller\Exception\InstallerException`.

This catch handles exceptions from the library. Symfony `OptionsResolver` exceptions and exceptions thrown by configuration callbacks do not necessarily extend `InstallerException`; handle them separately when the module must convert those failures to `false` too.

## Preserve processing semantics

- Keep handlers and their items in dependency order. Installation and uninstallation both use the declared order; uninstallation is not automatically reversed.
- Do not assume rollback. If one handler throws, earlier work remains and later handlers are not run.
- Treat exceptions as the failure contract. `Installer` does not use boolean values returned by handlers.
- Expect factory-time failures from item validation, Symfony `OptionsResolver`, configuration callbacks, and invalid custom handler classes.
- Build the installer near the lifecycle call when configuration values or environment state are evaluated dynamically.

## Load component guidance

Before creating or modifying a built-in handler, read every applicable reference. Do not infer handler options or behavior from memory.

- Read [configuration.md](references/configuration.md) for configuration names, prefixes, and value conversion.
- Read [database.md](references/database.md) for SQL sources, placeholders, table removal, and retained data.
- Read [hooks.md](references/hooks.md) for hook names and PrestaShop version constraints.
- Read [tabs.md](references/tabs.md) for menu hierarchy, multilingual names, and Symfony tabs.

When an installer combines multiple built-in handlers, read the reference for each one.

## Create a custom handler

Implement every method in `HandlerInterface`: `createFrom(Module $module, array $items)`, `getItems()`, `addItem()`, `removeItem()`, `install()`, and `uninstall()`. Alternatively, extend `AbstractHandler` and implement `getItemClassName()`, `install()`, and `uninstall()` using an `ItemInterface` implementation. Register the handler FQCN as the key:

```php
Installer::createFrom($module, [
    MyCustomHandler::class => [
        ['id' => 123],
    ],
]);
```

Throw an exception extending `InstallerException`, such as `HandlerException`, when an operation fails. Returning `false` alone does not make `Installer::install()` or `Installer::uninstall()` fail.

## Verify changes

- Verify install, uninstall, and a deliberate failure path.
- Test partial installations when ordering or dependencies matter.
- Test the module on every PrestaShop version it supports.
