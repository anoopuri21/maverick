<?php

namespace App\Services\Concerns;

use Illuminate\Support\Facades\Schema;

/**
 * Shared database discovery for media maintenance services (same rules as
 * MediaUsageService so audit/merge/clean always see the same columns).
 */
trait ScansMediaTables
{
    protected function looksLikeMediaUrlColumn(string $name): bool
    {
        $name = strtolower($name);

        return str_contains($name, 'image')
            || str_contains($name, 'logo')
            || str_contains($name, 'photo')
            || str_contains($name, 'thumbnail')
            || str_contains($name, 'banner')
            || str_contains($name, 'avatar')
            || str_contains($name, 'favicon')
            || str_contains($name, 'icon')
            || str_ends_with($name, '_url');
    }

    protected function isAssetIdColumn(string $name): bool
    {
        return $name === 'media_asset_id' || str_ends_with($name, '_asset_id');
    }

    protected function isJsonColumn(string $name, string $type): bool
    {
        return in_array($type, ['json', 'jsonb'], true)
            || (in_array($type, ['text', 'longtext', 'mediumtext'], true)
                && ($name === 'payload' || str_contains($name, 'json')));
    }

    protected function isTextColumnType(string $type): bool
    {
        return in_array($type, ['text', 'longtext', 'mediumtext', 'string', 'varchar', 'char'], true);
    }

    /**
     * @return list<string>
     */
    protected function tables(): array
    {        if (method_exists(Schema::getFacadeRoot(), 'getTableListing')) {
            $tables = Schema::getTableListing();
        } else {
            $tables = Schema::getAllTables();
        }

        return array_values(array_filter(array_map(function ($table) {
            if (is_string($table)) {
                return $table;
            }

            if (is_object($table)) {
                return $table->name ?? $table->tablename ?? $table->table_name ?? null;
            }

            return null;
        }, $tables)));
    }

    /**
     * Schema drivers may return qualified names (SQLite: "main.settings").
     * Strip the schema prefix for skip-list comparisons and source labels.
     * Queries must keep using the raw name.
     */
    protected function baseTable(string $table): string
    {
        $pos = strrpos($table, '.');

        return $pos === false ? $table : substr($table, $pos + 1);
    }

    /**
     * @return list<array{name: string, type?: string, type_name?: string}>
     */
    protected function columns(string $table): array
    {
        if (method_exists(Schema::getFacadeRoot(), 'getColumns')) {
            return Schema::getColumns($table);
        }

        return array_map(
            static fn (string $name) => ['name' => $name, 'type_name' => ''],
            Schema::getColumnListing($table)
        );
    }

    /**
     * @param  list<array{name: string}>  $columns
     */
    protected function chunkColumn(string $table, array $columns): string
    {
        $names = array_column($columns, 'name');

        if (in_array('id', $names, true)) {
            return 'id';
        }

        return $names[0] ?? 'id';
    }
}
