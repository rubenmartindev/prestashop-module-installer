# Hooks Handler

Add hook items under the plural `hooks` key passed to `Installer::createFrom()`.

## Define items

| Option | Accepted type | Default | Purpose |
|---|---|---|---|
| `name` | `string` | Required | PrestaShop hook name |
| `prestashop_version` | `string`, `array`, or `null` | `null` | Conditions for registration |

```php
Installer::createFrom($module, [
    'hooks' => [
        ['name' => 'displayHeader'],
        ['name' => 'displayFooter', 'prestashop_version' => '>=1.7'],
        [
            'name' => 'displayAdminListBefore',
            'prestashop_version' => [
                'min' => '>=1.7',
                'max' => '<8.0',
            ],
        ],
    ],
]);
```

Names are trimmed and validated with PrestaShop's `Validate::isHookName()` while items are built.

## Apply version conditions

Use one of these forms:

```php
null
'>=1.7'
['min' => '>=1.7']
['min' => '>=1.7', 'max' => '<8.0']
['min' => null, 'max' => '<8.0']
```

A string is normalized as the `min` condition. For an array, include the `min` key; `max` is optional. Each non-null value must be a comparison accepted by `PrestaShopVersionChecker`.

The comparison strings are authoritative: `min` and `max` are labels, not operators added by the library. Write `>=1.7`, not just `1.7`.

Each condition is checked independently during installation. If either condition is false, registration is skipped without raising an exception.

## Understand lifecycle behavior

Installation calls `$module->registerHook($name)`. A failed registration raises `FailedRegisterHookException`.

The hook handler's `uninstall()` is a no-op and returns `true`. Rely on the normal PrestaShop module lifecycle for removal of module-hook associations; do not expect this handler to call `unregisterHook()`.

## Avoid common mistakes

- Omit the entire `hooks` key instead of passing an empty array.
- Check ranges for contradictions; incompatible conditions silently prevent registration.
- Do not assume a hook skipped on one PrestaShop version is an installation failure.
