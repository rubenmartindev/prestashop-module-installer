# Configuration Handler

Add configuration items under the `configuration` key passed to `Installer::createFrom()`.

## Define items

| Option | Accepted type | Default | Purpose |
|---|---|---|---|
| `name` | `string` | Required | Configuration key before normalization |
| `value` | `callable`, `bool`, `string`, `array`, `object`, or `null` | `null` | Value to store |
| `prefix` | `string` or `null` | Module name | Prefix applied to the key |

```php
Installer::createFrom($module, [
    'configuration' => [
        ['name' => 'is_enabled', 'value' => true],
        ['name' => 'settings', 'value' => ['mode' => 'safe']],
        [
            'name' => 'generated_value',
            'value' => function () {
                return 'value';
            },
            'prefix' => 'custom_prefix',
        ],
    ],
]);
```

## Predict stored names

Names and prefixes are trimmed and uppercased. Trailing underscores are removed from the prefix, then one underscore joins both parts.

- Module `my_module` plus name `is_enabled` becomes `MY_MODULE_IS_ENABLED`.
- Prefix `custom_prefix_` plus name `value` becomes `CUSTOM_PREFIX_VALUE`.
- Passing `prefix => null` through `ConfigurationItem::createFrom()` uses the module name; it does not disable prefixing.
- Use `prefix => ''` when a configuration name must have no prefix.

Choose both `name` and `prefix` so the final concatenated key is a valid PrestaShop configuration name. Invalid or empty `name` values fail while the installer is being built, but the implementation does not validate the prefix or the final concatenated key.

## Predict stored values

- Convert `true` and `false` to strings `'1'` and `'0'`.
- Trim strings.
- Encode arrays with `json_encode()`.
- Serialize objects with `serialize()`.
- Preserve `null`.
- Invoke callables while the item is created, not when `install()` later runs.

Do not use an object when a stable, portable configuration representation is required. Prefer scalar values or explicit JSON-compatible arrays.

## Understand lifecycle behavior

Installation calls `Configuration::updateValue()` for every item. Uninstallation calls `Configuration::deleteByName()` for every item. There is no option to retain a declared configuration entry during uninstall.

A `false` return from those operations raises `FailedAddConfigurationException` or `FailedDeleteConfigurationException`, respectively. Exceptions thrown by PrestaShop are propagated as-is. Validation and callback failures can occur before handler execution.

## Avoid common mistakes

- Omit the entire `configuration` key instead of passing an empty array.
- Do not expect whitespace at the beginning or end of a string value to survive.
- Ensure arrays can be encoded as JSON; encoding errors are not handled explicitly.
- Account for callable side effects whenever `Installer::createFrom()` is called.
