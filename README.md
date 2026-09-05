# PrestaShop Module Installer

`rubenmartindev/prestashop-module-installer` is a PHP library that provides
install and uninstall helpers for PrestaShop modules.

## Overview

This library simplifies common PrestaShop module installation and uninstallation
tasks, including:

- hook registration and removal
- configuration entry management
- database table creation and removal
- tab (menu) registration and removal

It is designed to be used inside PrestaShop module lifecycle methods (install
and uninstall).

## Installation

Install via Composer in your PrestaShop module:

```bash
composer require rubenmartindev/prestashop-module-installer
```

### Agent Skill

Install the bundled Agent Skill with [`skills`](https://skills.sh/):

```bash
npx skills add rubenmartindev/prestashop-module-installer
```

## Requirements

- PHP >= 5.6.0
- PrestaShop 1.6.x / 1.7.x / 8.x / 9.x

## Development Environment

The repository includes a Docker Compose environment for working with a
specific PrestaShop version. The PrestaShop core is mounted in
`prestashop/<PS_VERSION_TAG>` so it can be inspected and modified locally.
Those installation files are ignored by Git.

Copy the environment template and start the default instance:

```bash
cp .env.dist .env
make up
```

The Compose project name is generated from `PS_VERSION_TAG`. Different versions
can run simultaneously when using different host ports:

```bash
make up PS_VERSION_TAG=1.6 PS_HTTP_PORT=8080
make up PS_VERSION_TAG=9 PS_HTTP_PORT=8090
```

Manage an instance by passing its version again:

```bash
make logs PS_VERSION_TAG=1.6
make down PS_VERSION_TAG=1.6
```

Additional Docker Compose options can be passed after `--`. Instance variables
can still be provided through `.env`, the environment, or directly on the
`make` command:

```bash
# Remove the containers and their volumes
make down -- --volumes

# Recreate a specific PrestaShop instance
make up PS_VERSION_TAG=9 PS_HTTP_PORT=8090 -- --force-recreate

# Show the last 100 log lines
make logs -- --tail 100

# Show a specific service
make ps -- prestashop
```

The database image is MySQL and its tag can be changed with `DB_VERSION_TAG`.

The development image also includes a `installerdemo` fixture module. It is
mounted as a regular PrestaShop module and requires the library from the
repository through a Composer path repository. The repository root is mounted
at `/workspace/prestashop-module-installer`, so changes to `src/` are available
immediately in the fixture.

Composer installs the fixture dependencies on startup. The fixture's
`composer.lock` and `vendor/` are local files and are not versioned. The
library tests can be run from the container shell:

```bash
make shell PS_VERSION_TAG=9
cd /workspace/prestashop-module-installer
composer tests
composer cs
```

## Usage

`RubenMartinDev\PrestaShopModuleInstaller\Installer` is designed to be used
inside your module. It defines the resources that will be installed or
uninstalled.

`Installer` uses a set of handlers (hooks, configuration, database, tabs). Each
handler defines a set of items that are processed during installation and
uninstallation.

Once handlers and their items are configured, you only need to call `install()`
or `uninstall()`.

### Use the helper `Installer::createFrom()`

The static method `Installer::createFrom()` makes it easy to create handlers and
items from an array.

```php
use RubenMartinDev\PrestaShopModuleInstaller\Exception\InstallerException;
use RubenMartinDev\PrestaShopModuleInstaller\Installer;
use RubenMartinDev\PrestaShopModuleInstaller\InstallerInterface;

class MyModule extends Module
{
    /**
     * {@inheritDoc}
     */
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

    /**
     * {@inheritDoc}
     */
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

    /**
     * @return InstallerInterface
     */
    private function getInstaller()
    {
        return Installer::createFrom(
            $this,
            [
                'configuration' => [
                    [
                        'name'        => 'is_enabled',
                        'value'       => true,
                    ],
                ],
                'database'      => [
                    [
                        'table_name'  => 'my_table',
                        'query'       => 'CREATE TABLE `{{DB_PREFIX}}my_table`;',
                    ],
                ],
                'hooks'         => [
                    [
                        'name'        => 'displayHeader',
                    ],
                ],
                'tabs'          => [
                    [
                        'class_name'  => 'AdminMyTab',
                        'name'        => 'My admin tab',
                    ],
                ],
            ],
        );
    }
}
```

### Use as a service

If your module uses its own container, for example using
[`prestashop/module-lib-service-container`](https://github.com/PrestaShopCorp/module-lib-service-container),
you can create your own service to simplify `Installer` configuration.

The service factory receives the same arguments as `Installer::createFrom()`.

```yml
services:

  my_module.installer:
    class: RubenMartinDev\PrestaShopModuleInstaller\Installer
    factory: [RubenMartinDev\PrestaShopModuleInstaller\Installer, createFrom]
    public: true
    arguments:
      - '@my_module'
      - configuration:
        - name: is_enabled
          value: true
        database:
        - table_name: my_table
          query: 'CREATE TABLE `{{DB_PREFIX}}my_table`;'
        hooks:
        - name: displayHeader
        tabs:
        - class_name: AdminMyTab
          name: My admin tab
```

Then retrieve the service from the container and call `install()` /
`uninstall()` in the same way as in the previous example.

## Installer Configuration

The `Installer` has a list of handlers that will be processed during
installation and uninstallation.

To specify which handlers will be used, pass an array with
[`configuration`](#configuration), [`hooks`](#hooks), [`database`](#database),
or [`tabs`](#tabs), and the items each one contains.

```php
Installer::createFrom(
    $myModule,
    [
        'configuration' => [
            // ...
        ],
        'hooks'         => [
            // ...
        ],
        'database'      => [
            // ...
        ],
        'tabs'          => [
            // ...
        ],
    ]
);
```

## Handlers and Items

Each handler contains a list of items executed during installation and
uninstallation. Both handlers and items have the static method `createFrom()` to
simplify configuration through an associative array.

### Configuration

This handler defines the new configuration entries (`ps_configuration`) that
will be added to PrestaShop.

#### Item

Represents a single configuration entry.

| Argument  | Type                                             | Default     | Description                       |
|-----------|--------------------------------------------------|-------------|-----------------------------------|
| `name`    | `string`                                         | _Required_  | Configuration name.               |
| `value`   | `callable\|bool\|string\|array\|object\|null`    | `null`      | Value to store in configuration.  |
| `prefix`  | `string\|null`                                   | `null`      | Prefix to add to `name`.          |

If `value` is a callable, it must return a result.

`value` transforms its value as follows:
  - Booleans are converted to strings (`'0'` or `'1'`).
  - Arrays are converted to JSON.
  - Objects are serialized.

If `prefix` is null, the module name is used by default. Otherwise, it is
prefixed to `name`.

#### Example

```php
Installer::createFrom(
    $myModule,
    [
        'configuration' => [
            [
                'name'    => 'my_boolean',    // --> MY_MODULE_MY_BOOLEAN
                'value'   => true,            // --> '1'
            ],
            [
                'name'    => 'my_string',     // --> MY_PREFIX_MY_STRING
                'value'   => 'abcdef',        // --> abcdef
                'prefix'  => 'my_prefix'
            ],
            [
                'name'    => 'my_array',      // --> MY_MODULE_MY_ARRAY
                'value'   => [                // --> {"email": "contact@example.com"}
                  'email'   => 'contact@example.com',
                ],
                'prefix'  => null,
            ],
            [
                'name'    => 'my_object',     // --> MY_MODULE_MY_OBJECT
                'value'   => new stdClass(),  // --> O:8:"stdClass":0:{}
            ],
            [
                'name'    => 'my_callback',   // --> OTHER_PREFIX_MY_CALLBACK
                'value'   => function () {    // --> my_value
                    return 'my_value';
                },
                'prefix'  => 'other_prefix',
            ],
            // ...
        ],
        // ...
    ],
);
```

### Database

This handler is used to create and remove tables in the database.

#### Item

Represents a single SQL statement.

| Argument      | Type            | Default     | Description                                                       |
|---------------|-----------------|-------------|-------------------------------------------------------------------|
| `table_name`  | `string`        | _Required_  | Table name.                                                       |
| `query`       | `string\|null`  | `null`      | Raw SQL.                                                          |
| `query_file`  | `string\|null`  | `null`      | Path to the SQL file.                                             |
| `keep_data`   | `bool`          | `false`     | Whether the table should be kept when the module is uninstalled.  |

Both `query` and `query_file` support the placeholders `{{DB_PREFIX}}`
and `{{ENGINE_TYPE}}`, which correspond to the PrestaShop constants
`_DB_PREFIX_` and `_MYSQL_ENGINE_` respectively.

If both `query` and `query_file` are set, the latter takes precedence and `query`
will be ignored.

#### Example

```php
Installer::createFrom(
    $myModule,
    [
        'database' => [
            [
                'table_name'  => 'my_table',
                'query'       => 'CREATE TABLE `{{DB_PREFIX}}my_table`;',
            ],
            [
                'table_name'  => 'my_another_table',
                'query_file'  => "{$myModule->getLocalPath()}/my_another_table.sql",
                'keep_data'   => true,
            ],
            // ...
        ],
        // ...
    ],
);
```

### Hooks

This handler is used to register hooks.

#### Item

Represents a single hook.

| Argument              | Type                  | Default     | Description                                   |
|-----------------------|-----------------------|-------------|-----------------------------------------------|
| `name`                | `string`              | _Required_  | Hook name.                                    |
| `prestashop_version`  | `string\|array\|null` | `null`      | PrestaShop version compatible with the hook.  |

When `prestashop_version` is null, the PrestaShop version is ignored and the hook
will be registered. If it is a string, the PrestaShop version is checked and the
hook will only be registered if the version constraint is satisfied. If it is an
array it must contain at least the `min` key to specify the minimum PrestaShop
version from which the hook will be registered, and the optional `max` key to
specify up to which PrestaShop version the hook should be registered.

`prestashop_version` internally uses
[rubenmartindev/prestashop-version-checker](https://github.com/rubenmartindev/prestashop-version-checker/)
for version validation and checking.

#### Example

```php
Installer::createFrom(
    $myModule,
    [
        'hooks' => [
            [
                'name'                => 'displayHeader',
            ],
            [
                'name'                => 'displayFooter',
                'prestashop_version'  => '>=1.7',
            ],
            [
                'name'                => 'displayAdminView',
                'prestashop_version'  => ['min' => '>=1.7'],
            ],
            [
                'name'                => 'displayAdminListBefore',
                'prestashop_version'  => ['min' => '>=1.7', 'max' => '<8.0'],
            ],
            // ...
        ],
        // ...
    ],
);
```

### Tabs

This handler defines and manages tabs (controllers).

#### Item

Represents a single tab.

| Argument          | Type            | Default     | Description                         |
|-------------------|-----------------|-------------|-------------------------------------|
| `class_name`      | `string`        | _Required_  | Controller class name.              |
| `name`            | `string\|array` | _Required_  | Name in the menu.                   |
| `parent_id`       | `string\|int`   | `-1`        | Parent controller ID or class name. |
| `position`        | `int`           | `0`         | Position in the tabs tree.          |
| `is_active`       | `bool`          | `true`      | Whether the tab is active.          |
| `is_enabled`      | `bool`          | `true`      | Whether the tab is available.       |
| `route_name`      | `string\|null`  | `null`      | Symfony route name.                 |
| `icon`            | `string\|null`  | `null`      | Icon name used in the menu.         |
| `wording`         | `string\|null`  | `null`      | Translation.                        |
| `wording_domain`  | `string\|null`  | `null`      | Translation domain for the tab.     |

When `name` is an array, it must be a numeric array. The array keys correspond to
`id_lang`, and the default language ID key (`PS_LANG_DEFAULT`) must always be
present.

When `parent_id` is a string, the tab ID will be resolved using the class name.

When `wording` is null, it will be set using the value of `name` for the default
PrestaShop language.

When `wording_domain` is null, it defaults to "Admin.Navigation.Menu".

The `is_enabled`, `route_name`, `icon`, `wording`, and `wording_domain` properties
may be ignored depending on the PrestaShop version.

#### Example

```php
Installer::createFrom(
    $myModule,
    [
        'tabs' => [
            [
                'class_name'      => 'AdminMyTab',
                'name'            => 'My admin tab',
            ],
            [
                'class_name'      => 'AdminMyAnotherTab',
                'name'            => [1 => 'My another tab in EN', 2 => 'My another tab in ES'],
                'parent_id'       => 10,
                'position'        => 2,
                'is_active'       => false,
            ],
            [
                'class_name'      => 'AdminMyExtraTab',
                'name'            => 'My extra tab',
                'parent_id'       => 'AdminParentOrders',
                'position'        => 3,
                'route_name'      => 'admin_my_module_my_extra_tab',
                'icon'            => 'extension',
                'wording'         => 'My extra tab',
                'wording_domain'  => 'Modules.MyModule.Navigation',
            ],
            // ...
        ],
        // ...
    ],
);
```

### Custom Handler

Although there are generic handlers for common tasks, you may need to carry out
custom actions such as modifying files, creating new records or performing other
tasks. To do this, you can create your own handler.

```php
namespace MyModule\Installer\Handler;

use Customer;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\Exception\HandlerException;
use RubenMartinDev\PrestaShopModuleInstaller\Handler\HandlerInterface;

final class MyCustomHandler implements HandlerInterface
{
    private $items;

    public function __construct(array $items)
    {
        $this->items = $items;
    }

    public static function createFrom(Module $module, array $items)
    {
        return new static($items);
    }

    public function install()
    {
        foreach ($this->items as $item) {
            $customer = new Customer();

            $customer->lastname   = $item['lastname'];
            $customer->firstname  = $item['firstname'];
            $customer->email      = $item['email'];
            $customer->passwd     = $item['passwd'];

            if (!$customer->add()) {
                throw new HandlerException('Failed to create customer ' . $item['email']);
            }
        }

        return true;
    }

    public function uninstall()
    {
        foreach ($this->items as $item) {
            $customer = Customer::getByEmail($item['email']);

            if (!$customer->delete()) {
                throw new HandlerException('Failed to delete customer ' . $item['email']);
            }
        }

        return true;
    }
}
```

To register our custom handler, we must add it to the list of handlers in our
`Installer`, using the FQCN of our class as the key.

```php
Installer::createFrom(
    $myModule,
    [
        \MyModule\Installer\Handler\MyCustomHandler::class => [
            [
                'lastname'  => 'John',
                'firstname' => 'Doe',
                'email'     => 'customer@example.com',
                'passwd'    => 'e64c7d89f26bd1972efa854d13d7dd61',
            ],
        ],
    ]
);
```
