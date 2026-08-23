<?php

namespace App\Http\Controllers;

use App\Models\PartnerLogo;
use App\Settings\AccreditationCinematicSettings;
use App\Settings\AccreditationsSeoSettings;
use App\Settings\AccreditationsPageSettings;
use App\Support\PublicContentCache;

class AccreditationController extends Controller
{
    public function index()
    {
        $cinematicSettings = safe_settings(AccreditationCinematicSettings::class);
        $cached = PublicContentCache::remember(PublicContentCache::ACCREDITATIONS, function () {
            $accreditationLogos = PartnerLogo::select('id', 'name', 'logo_url', 'type', 'sort_order')
                ->whereIn('type', ['accreditation', 'recognition'])
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get();

            $awardLogos = PartnerLogo::select('id', 'name', 'logo_url', 'type', 'description', 'sort_order')
                ->where('type', 'award')
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get();

            return [
                'accreditationLogos' => $accreditationLogos->toArray(),
                'awardLogos' => $awardLogos->toArray(),
            ];
        });

        return view('pages.accreditations', [
            'accreditationsPage' => safe_settings(AccreditationsPageSettings::class),
            'accreditationLogos' => PublicContentCache::hydrateRows(PartnerLogo::class, $cached['accreditationLogos'] ?? []),
            'awardLogos' => PublicContentCache::hydrateRows(PartnerLogo::class, $cached['awardLogos'] ?? []),
            'cinematicSettings' => $cinematicSettings,
            'accreditationsSeo' => safe_settings(AccreditationsSeoSettings::class),
        ]);
    }
}
