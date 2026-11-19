<?php

namespace App\Support;

final class MlpProse
{
    /**
     * Sanitized admin HTML from Filament RichEditor, or escaped plain text wrapped in <p>.
     */
    public static function html(?string $content): string
    {
        $content = trim((string) $content);
        if ($content === '') {
            return '';
        }

        if (str_contains($content, '<')) {
            return sanitize_rich_html($content);
        }

        return '<p>'.e($content).'</p>';
    }
}
