<?php

namespace App\Http\Controllers;

use App\Models\PartnerLogo;
use Illuminate\Http\Request;

class AccreditationController extends Controller
{
    public function index()
    {
        // Section 1: Accreditations & Partnerships (Accreditation + Alumni types)
        $accreditationLogos = PartnerLogo::select('id', 'name', 'logo_url', 'type', 'sort_order')
            ->whereIn('type', ['accreditation', 'alumni'])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        // Section 2: Awards & Recognition (Award + Recognition types)
        $awardLogos = PartnerLogo::select('id', 'name', 'logo_url', 'type', 'sort_order')
            ->whereIn('type', ['award', 'recognition'])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('pages.accreditations', compact(
            'accreditationLogos',
            'awardLogos'
        ));
    }
}
