<?php

namespace App\Http\Controllers;

use App\Models\GlobalPathway;

class GlobalPathwayController extends Controller
{
    /** /global-pathways/pathway-programs */
    public function pathwayPrograms()
    {
        $page = GlobalPathway::where('type', 'pathway-programs')
            ->where('is_active', true)->first();

        return view('pages.global-pathways.pathway-programs', compact('page'));
    }

    /** /global-pathways/global-opportunities */
    public function globalOpportunities()
    {
        $page = GlobalPathway::where('type', 'global-opportunities')
            ->where('is_active', true)->first();

        return view('pages.global-pathways.global-opportunities', compact('page'));
    }

    /** Fallback index for /global-pathways */
    public function index()
    {
        return redirect()->route('global-pathways.programs');
    }
}
