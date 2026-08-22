<?php

namespace App\Support;

class EdutainmentIcons
{
    public static function options(): array
    {
        return [
            'graduation-cap' => 'Graduation Cap',
            'graduation-cap-user' => 'Graduation Cap + User',
            'monitor' => 'Monitor',
            'book' => 'Book',
            'building' => 'Building',
            'user-badge' => 'User Badge',
            'briefcase' => 'Briefcase',
            'cpu' => 'CPU / Tech',
            'users' => 'Users',
            'star' => 'Star',
            'dollar' => 'Dollar',
            'globe-check' => 'Globe Check',
            'wrench' => 'Wrench',
            'grid' => 'Grid',
            'globe' => 'Globe',
            'message' => 'Message',
            'smile' => 'Smile',
            'calendar' => 'Calendar',
            'balance' => 'Balance',
            'shield' => 'Shield',
            'user-line' => 'User Line',
            'globe-line' => 'Globe Line',
            'clock' => 'Clock',
            'user' => 'User',
        ];
    }

    public static function svg(?string $key, int $size = 24): string
    {
        if (! $key || ! array_key_exists($key, self::options())) {
            return '';
        }

        $paths = self::paths()[$key] ?? '';

        return '<svg viewBox="0 0 24 24" width="'.$size.'" height="'.$size.'" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">'.$paths.'</svg>';
    }

    private static function paths(): array
    {
        return [
            'graduation-cap' => '<path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/>',
            'graduation-cap-user' => '<path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/><circle cx="12" cy="7" r="3"/>',
            'monitor' => '<rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/><path d="M12 7v4M10 9h4"/>',
            'book' => '<path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/><path d="M8 7h8M8 11h6"/>',
            'building' => '<path d="M3 21h18M3 7v1a3 3 0 006 0V7m0 1a3 3 0 006 0V7m0 1a3 3 0 006 0V7"/><path d="M5 21V10.5M19 21V10.5"/><path d="M3 10.5h18"/>',
            'user-badge' => '<path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/><path d="M16 3l2 2-2 2M18 5h-4"/>',
            'briefcase' => '<rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/>',
            'cpu' => '<rect x="4" y="4" width="16" height="16" rx="2"/><path d="M9 9h6v6H9z"/><path d="M9 1v3M15 1v3M9 20v3M15 20v3M20 9h3M20 14h3M1 9h3M1 14h3"/>',
            'users' => '<path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/>',
            'star' => '<path d="M12 2l2.4 7.2H22l-6 4.8 2.4 7.2L12 16.8 5.6 21.2 8 14 2 9.2h7.6z"/>',
            'dollar' => '<path d="M12 2v20M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/>',
            'globe-check' => '<path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/><path d="M7 12l3 3 7-7"/>',
            'wrench' => '<path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z"/>',
            'grid' => '<path d="M3 3h18v18H3z"/><path d="M3 9h18M9 3v18"/>',
            'globe' => '<circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/>',
            'message' => '<path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>',
            'smile' => '<circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2M9 9h.01M15 9h.01"/>',
            'calendar' => '<rect x="3" y="4" width="18" height="17" rx="2"/><path d="M3 9h18M9 4v5M12 4v5M15 4v5M8 15h8M8 18h5"/>',
            'balance' => '<path d="M12 3v18M5 7h14M8 21l4-4 4 4"/><path d="M5 7l-3 6a3 3 0 006 0l-3-6zM19 7l-3 6a3 3 0 006 0l-3-6z"/>',
            'shield' => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="M9 12l2 2 4-4"/>',
            'user-line' => '<circle cx="12" cy="8" r="3"/><path d="M5 21a7 7 0 0114 0"/><path d="M12 11v10"/>',
            'globe-line' => '<circle cx="12" cy="12" r="10"/><path d="M2 12h20"/>',
            'clock' => '<circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>',
            'user' => '<path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>',
        ];
    }
}
