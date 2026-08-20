<?php

namespace App\Http\Controllers;

use App\Models\GlobalPathway;

class GlobalPathwayController extends Controller
{
    /** /pathway-programs — page from slug */
    public function pathwayPrograms()
    {
        $page = GlobalPathway::where('slug', 'pathway-programs')->where('is_active', true)->first();
        return view('pages.global-pathways.pathway-programs', compact('page'));
    }

    /** /global-opportunities — page from slug */
    public function globalOpportunities()
    {
        $page = GlobalPathway::where('slug', 'global-opportunities')->where('is_active', true)->first();
        return view('pages.global-pathways.global-opportunities', compact('page'));
    }
}
