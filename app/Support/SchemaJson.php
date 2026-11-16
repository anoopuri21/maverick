<?php

namespace App\Support;

use JsonException;

class SchemaJson
{
    /** Return script-safe JSON-LD, or null for empty or malformed CMS input. */
    public static function encode(?string $input): ?string
    {
        if ($input === null || trim($input) === '') {
            return null;
        }

        try {
            $value = json_decode($input, false, 512, JSON_THROW_ON_ERROR);
            if (! is_object($value) && ! is_array($value)) {
                return null;
            }

            return json_encode($value, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
        } catch (JsonException) {
            return null;
        }
    }
}
