<?php

namespace App\Support;

class MpIcons
{
    public static function options(): array
    {
        return [
            'chat' => 'Chat',
            'shield' => 'Shield',
            'graduation-cap' => 'Graduation Cap',
            'map-pin' => 'Map Pin',
            'file-check' => 'File Check',
            'plane' => 'Plane',
            'award' => 'Award',
        ];
    }

    public static function svg(?string $key, int $size = 24): string
    {
        if (! $key || ! array_key_exists($key, self::options())) {
            return '';
        }

        $paths = self::paths()[$key] ?? '';

        return '<svg viewBox="0 0 24 24" width="'.$size.'" height="'.$size.'" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">'.$paths.'</svg>';
    }

    private static function paths(): array
    {
        return [
            'chat' => '<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>',
            'shield' => '<path d="M9 12l2 2 4-4"/><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>',
            'graduation-cap' => '<path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/>',
            'map-pin' => '<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>',
            'file-check' => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="M9 15l2 2 4-4"/>',
            'plane' => '<path d="M10.5 22l-3-5M3 14h18l-2.5 8H5.5z"/><path d="M10.5 22L9 14l3-5 3 5-1.5 8"/>',
            'award' => '<circle cx="12" cy="8" r="6"/><path d="M15.5 13l2.5 8-6-3.5L6 21l2.5-8"/>',
        ];
    }
}
