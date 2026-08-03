<?php

namespace App\Http\Controllers;

use App\Models\PartnerLogo;
use Illuminate\Http\Request;

class AccreditationController extends Controller
{
    public function index()
    {
        // Accreditations & Partnerships (accreditation + recognition types)
        $accreditationLogos = PartnerLogo::select('id', 'name', 'logo_url', 'type', 'sort_order')
            ->whereIn('type', ['accreditation', 'recognition'])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        // Awards & Recognition (award + recognition types)
        $awardLogos = PartnerLogo::select('id', 'name', 'logo_url', 'type', 'sort_order')
            ->whereIn('type', ['award', 'recognition'])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        // For backward compatibility - also get by individual types
        $partnerUniversityLogos = PartnerLogo::select('id', 'name', 'logo_url', 'sort_order')
            ->where('type', 'accreditation')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $institutionalLogos = PartnerLogo::select('id', 'name', 'logo_url', 'sort_order')
            ->where('type', 'recognition')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $awardOnlyLogos = PartnerLogo::select('id', 'name', 'logo_url', 'sort_order')
            ->where('type', 'award')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('pages.accreditations', compact(
            'accreditationLogos',
            'awardLogos',
            'partnerUniversityLogos',
            'institutionalLogos',
            'awardOnlyLogos'
        ));
    }
}
