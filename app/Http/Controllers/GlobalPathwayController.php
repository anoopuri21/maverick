<?php

namespace App\Http\Controllers;

use App\Models\GlobalPathway;

class GlobalPathwayController extends Controller
{
    /** /pathway-programs — page from slug (null-safe) */
    public function pathwayPrograms()
    {
        $page = GlobalPathway::where('slug', 'pathway-programs')->where('is_active', true)->first()
            ?? $this->defaultPage('pathway-programs');

        return view('pages.global-pathways.pathway-programs', compact('page'));
    }

    /** /global-opportunities — page from slug (null-safe) */
    public function globalOpportunities()
    {
        $page = GlobalPathway::where('slug', 'global-opportunities')->where('is_active', true)->first()
            ?? $this->defaultPage('global-opportunities');

        return view('pages.global-pathways.global-opportunities', compact('page'));
    }

    /** Empty stub so a missing/unseeded row never crashes the page. */
    protected function defaultPage(string $slug): object
    {
        return (object) [
            'title'          => $slug === 'pathway-programs' ? 'Pathway Programs' : 'Global Opportunities',
            'eyebrow'        => $slug === 'pathway-programs' ? 'Global Pathways' : 'Global Opportunities',
            'heading'        => $slug === 'pathway-programs' ? 'Pathways to a ' : 'Opportunities Without ',
            'heading_italic' => $slug === 'pathway-programs' ? 'Global Degree' : 'Borders',
            'intro'          => null,
            'image_url'      => null,
            'items_list'     => [],
            'seo'            => [],
        ];
    }
}
