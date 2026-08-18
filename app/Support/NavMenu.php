<?php

namespace App\Support;

use App\Models\Program;
use App\Models\ProgramCategory;
use Illuminate\Support\Facades\Cache;

/**
 * Single source of truth for the Programs mega-menu.
 *
 * Returns an array of categories → programs → universities, so both the
 * desktop mega-menu and the mobile drawer render from the same data
 * (no more ~80 duplicated hardcoded links).
 *
 * Structure:
 * [
 *   ['name' => 'Diplomas', 'slug' => 'diplomas', 'viewAll' => '/programs',
 *    'programs' => [
 *       ['title' => '...', 'url' => '/programs/x', 'university' => '...'],
 *    ]],
 *   ...
 * ]
 */
class NavMenu
{
    public static function programs(): array
    {
        return Cache::remember('navmenu.programs', 300, function () {
            $categories = ProgramCategory::with([
                    'programs' => fn ($q) => $q->where('is_active', true)
                        ->with('universityPartner')
                        ->orderBy('sort_order')->orderBy('title'),
                ])
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get();

            return $categories->map(function ($category) {
                return [
                    'name'     => $category->name,
                    'slug'     => $category->slug,
                    'icon'     => $category->icon,
                    'viewAll'  => route('programs.index'),
                    'programs' => $category->programs->map(function ($p) {
                        return [
                            'title'      => $p->title,
                            'url'        => route('programs.show', $p->slug),
                            'university' => $p->universityPartner->name ?? null,
                        ];
                    })->values()->all(),
                ];
            })->values()->all();
        });
    }
}
