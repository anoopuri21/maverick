<?php

namespace App\Http\Controllers;

use App\Models\GlobalPathway;

class GlobalPathwayController extends Controller
{
    /** /global-pathways — single hub page with tabbed sections */
    public function index()
    {
        $pathways = GlobalPathway::where('type', 'pathway-programs')->where('is_active', true)->first();
        $opportunities = GlobalPathway::where('type', 'global-opportunities')->where('is_active', true)->first();

        return view('pages.global-pathways.index', compact('pathways', 'opportunities'));
    }

    /** @deprecated kept for old direct links — redirect to hub */
    public function pathwayPrograms()
    {
        return redirect()->route('global-pathways.index');
    }

    /** @deprecated kept for old direct links — redirect to hub */
    public function globalOpportunities()
    {
        return redirect()->route('global-pathways.index');
    }
}
