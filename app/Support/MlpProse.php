<?php

namespace App\Support;

final class MlpProse
{
    /**
     * Trusted admin HTML from Filament RichEditor, or escaped plain text wrapped in <p>.
     */
    public static function html(?string $content): string
    {
        $content = trim((string) $content);
        if ($content === '') {
            return '';
        }

        if (str_contains($content, '<')) {
            return $content;
        }

        return '<p>'.e($content).'</p>';
    }
}
