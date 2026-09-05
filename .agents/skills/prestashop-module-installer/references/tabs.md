# Tabs Handler

Add tab items under the plural `tabs` key passed to `Installer::createFrom()`.

## Define items

| Option | Accepted type | Default | Purpose |
|---|---|---|---|
| `class_name` | `string` | Required | Controller class name and tab identity |
| `name` | `string` or `array` | Required | Menu label |
| `parent_id` | `int` or `string` | `-1` | Parent ID or parent class name |
| `position` | `int` | `0` | Position in the tab tree |
| `is_active` | `bool` | `true` | Active state |
| `is_enabled` | `bool` | `true` | Availability state |
| `route_name` | `string` or `null` | `null` | Symfony route name |
| `icon` | `string` or `null` | `null` | Menu icon |
| `wording` | `string` or `null` | Default-language name | Translation wording |
| `wording_domain` | `string` or `null` | `Admin.Navigation.Menu` | Translation domain |

```php
Installer::createFrom($module, [
    'tabs' => [
        [
            'class_name' => 'AdminMyModule',
            'name' => 'My module',
            'parent_id' => 0,
        ],
        [
            'class_name' => 'AdminMyModuleSettings',
            'name' => [1 => 'Settings', 2 => 'Configuracion'],
            'parent_id' => 'AdminMyModule',
            'route_name' => 'admin_my_module_settings',
            'icon' => 'settings',
        ],
    ],
]);
```

## Choose the parent

- Use `-1` for a hidden tab. This is the default.
- Use `0` for a root tab.
- Use an existing numeric tab ID.
- Use a parent tab's `class_name` and let installation resolve its ID.

Place a parent item before child items that refer to it in the same handler. A missing parent raises `ParentTabNotFoundException`.

## Define translated names

A string is copied to every installed language. An array must:

- Be non-empty.
- Use installed integer `id_lang` values as keys; unrelated language keys are not retained.
- Contain non-empty string values.
- Include `PS_LANG_DEFAULT`.

Missing non-default languages inherit the default-language name. Building an item queries the current default language and installed languages immediately.

## Understand lifecycle behavior

Installation looks up an existing tab by `class_name` and saves the declared values, allowing the same definition to create or update it. A failed save raises `FailedToCreateTabException`.

Uninstallation removes every tab associated with the module, not only tabs present in the current installer definition. A failed deletion raises `FailedToDeleteTabException`.

Some tab properties vary across supported PrestaShop versions. Verify `is_enabled`, `route_name`, `icon`, `wording`, and `wording_domain` on each target version.

## Avoid common mistakes

- Do not assume the default parent is root; it is hidden (`-1`).
- Do not use empty strings for optional textual values; use `null`.
- Put parent definitions before children.
- Account for uninstall removing all tabs owned by the module.
- Omit the entire `tabs` key instead of passing an empty array.
