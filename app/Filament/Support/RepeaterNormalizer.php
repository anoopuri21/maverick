<?php

namespace App\Filament\Support;

class RepeaterNormalizer
{
    /**
     * @param  array<int, mixed>  $rows
     * @param  array<int, string>|null  $requiredKeys
     * @return array<int, mixed>
     */
    public static function stripEmptyRows(array $rows, ?array $requiredKeys = null): array
    {
        $normalized = [];

        foreach ($rows as $row) {
            if (! is_array($row)) {
                if (is_string($row) && trim($row) !== '') {
                    $normalized[] = $row;
                }

                continue;
            }

            if (self::rowIsEmpty($row, $requiredKeys)) {
                continue;
            }

            $normalized[] = self::normalizeRow($row);
        }

        return array_values($normalized);
    }

    /**
     * @param  array<string, mixed>  $row
     * @param  array<int, string>|null  $requiredKeys
     */
    protected static function rowIsEmpty(array $row, ?array $requiredKeys): bool
    {
        if ($requiredKeys !== null) {
            foreach ($requiredKeys as $key) {
                if (self::valueIsPresent($row[$key] ?? null)) {
                    return false;
                }
            }

            return true;
        }

        foreach ($row as $value) {
            if (is_array($value)) {
                if ($value !== []) {
                    return false;
                }

                continue;
            }

            if (self::valueIsPresent($value)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param  array<string, mixed>  $row
     * @return array<string, mixed>
     */
    protected static function normalizeRow(array $row): array
    {
        foreach ($row as $key => $value) {
            if (is_array($value)) {
                $row[$key] = self::stripEmptyRows($value);
            }
        }

        return $row;
    }

    protected static function valueIsPresent(mixed $value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        if (is_numeric($value)) {
            return true;
        }

        if (is_string($value)) {
            return trim($value) !== '';
        }

        if (is_array($value)) {
            return $value !== [];
        }

        return $value !== null;
    }
}
