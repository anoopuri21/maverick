<?php

namespace App\Http\Controllers;

use App\Models\PartnerLogo;
use App\Settings\AccreditationCinematicSettings;
use App\Settings\AccreditationsSeoSettings;
use Illuminate\Http\Request;

class AccreditationController extends Controller
{
    public function index(AccreditationCinematicSettings $cinematicSettings)
    {
        // Section 1: Accreditations & Partnerships (Accreditation + Alumni types)
        $accreditationLogos = PartnerLogo::select('id', 'name', 'logo_url', 'type', 'sort_order')
            ->whereIn('type', ['accreditation', 'recognition'])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        // Section 2: Awards & Recognition (Award types only)
        $awardLogos = PartnerLogo::select('id', 'name', 'logo_url', 'type', 'description', 'sort_order')
            ->where('type', 'award')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('pages.accreditations', [
            'accreditationLogos' => $accreditationLogos,
            'awardLogos' => $awardLogos,
            'cinematicSettings' => $cinematicSettings,
            'accreditationsSeo' => app(AccreditationsSeoSettings::class),
        ]);
    }
}
