# Database Handler

Add database items under the `database` key passed to `Installer::createFrom()`.

## Define items

| Option | Accepted type | Default | Purpose |
|---|---|---|---|
| `table_name` | `string` | Required | Unprefixed table name used during uninstall |
| `query` | `string` or `null` | `null` | Embedded installation SQL |
| `query_file` | `string` or `null` | `null` | Readable installation SQL file |
| `keep_data` | `bool` | `false` | Skip table removal during uninstall |

```php
Installer::createFrom($module, [
    'database' => [
        [
            'table_name' => 'my_record',
            'query' => 'CREATE TABLE `{{DB_PREFIX}}my_record` (
                `id_record` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                PRIMARY KEY (`id_record`)
            ) ENGINE={{ENGINE_TYPE}};',
        ],
        [
            'table_name' => 'my_archive',
            'query_file' => $module->getLocalPath() . '/sql/my_archive.sql',
            'keep_data' => true,
        ],
    ],
]);
```

## Write installation SQL

- Use `{{DB_PREFIX}}` for `_DB_PREFIX_`.
- Use `{{ENGINE_TYPE}}` for `_MYSQL_ENGINE_`.
- Provide `table_name` without the database prefix.
- Ensure the table created by the SQL matches `table_name`; the library does not compare them.
- Prefer one statement per item. File contents are sent to one `Db::execute()` call and are not split into statements.

When both `query` and `query_file` are present, `query_file` wins. The file path must exist and be readable when the item is built. The file is read, checked for empty SQL, expanded, and cached on the first `getSQL()` call, normally during installation.

## Understand lifecycle behavior

Installation executes the resolved SQL once per item. Uninstallation issues:

```sql
DROP TABLE IF EXISTS `<DB_PREFIX><table_name>`
```

Items with `keep_data => true` are skipped during uninstall. There is no global transaction or rollback across database items or handlers.

`PrestaShopDatabaseException` is wrapped in `FailedToExecuteQueryException`. Be aware that the current handler does not treat a plain `false` return from `Db::execute()` as failure.

## Avoid common mistakes

- Do not include `_DB_PREFIX_` in `table_name`.
- Do not omit both `query` and `query_file`; construction succeeds, but installation fails with `SQLIsEmptyException`.
- Changes made before the first `getSQL()` call are read; changes made after that call do not affect the cached SQL.
- Do not put secrets in SQL that may appear in an exception message.
- Omit the entire `database` key instead of passing an empty array.
