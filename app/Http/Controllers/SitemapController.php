<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Insight;
use App\Models\Program;
use App\Settings\AccreditationsSeoSettings;
use App\Settings\ContactSeoSettings;
use App\Settings\CsrSeoSettings;
use App\Settings\DualMbaSeoSettings;
use App\Settings\EdutainmentSeoSettings;
use App\Settings\EventsSeoSettings;
use App\Settings\GbpSeoSettings;
use App\Settings\GlobalOpportunitiesSeoSettings;
use App\Settings\GlobalPartnersSeoSettings;
use App\Settings\HomepageSeoSettings;
use App\Settings\LeadershipSeoSettings;
use App\Settings\MbaMastersSeoSettings;
use App\Settings\MediaGallerySeoSettings;
use App\Settings\MpSeoSettings;
use App\Settings\OurStorySeoSettings;
use App\Settings\PathwayProgramsSeoSettings;
use App\Settings\PrivacySeoSettings;
use App\Settings\ProgramsListingSeoSettings;
use App\Settings\StudentSuccessSeoSettings;
use App\Settings\TermsSeoSettings;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    private const CHUNK_SIZE = 100;

    private const CACHE_TTL_MINUTES = 30;

    public function __invoke()
    {
        $cacheKey = 'sitemap.xml.'.hash('sha256', request()->getSchemeAndHttpHost());

        try {
            $xml = Cache::get($cacheKey);
        } catch (\Throwable) {
            $xml = null;
        }

        if (! is_string($xml) || $xml === '') {
            $xml = $this->renderSitemap();
            $expiresAt = now()->addMinutes(self::CACHE_TTL_MINUTES);

            try {
                Cache::put($cacheKey, $xml, $expiresAt);
            } catch (\Throwable) {
            }
        }

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
        ]);
    }

    private function renderSitemap(): string
    {
        $urls = [];

        foreach ($this->staticPages() as [$routeName, $settingsClass]) {
            $settings = $settingsClass ? safe_settings($settingsClass) : null;

            if ($this->hasNoindex($settings->robots ?? null)) {
                continue;
            }

            $routeUrl = route($routeName);
            $loc = $this->canonicalUrl($settings->canonical_url ?? null, $routeUrl);
            $this->addUrl($urls, $loc, null);
        }

        Program::query()
            ->select(['id', 'slug', 'updated_at'])
            ->where('is_active', true)
            ->hasPublicSlug()
            ->with('seo:id,seoable_id,seoable_type,canonical_url,robots')
            ->chunkById(self::CHUNK_SIZE, function ($programs) use (&$urls): void {
                foreach ($programs as $program) {
                    $seo = $program->seo;

                    if ($this->hasNoindex($seo?->robots)) {
                        continue;
                    }

                    $routeUrl = route('programs.show', $program->slug);
                    $loc = $this->canonicalUrl($seo?->canonical_url, $routeUrl);
                    $this->addUrl($urls, $loc, $program->updated_at?->toAtomString());
                }
            });

        $eventsSeo = safe_settings(EventsSeoSettings::class);

        if (! $this->hasNoindex($eventsSeo->robots ?? null)) {
            Event::query()
                ->select(['id', 'slug', 'updated_at'])
                ->where('is_active', true)
                ->chunkById(self::CHUNK_SIZE, function ($events) use (&$urls): void {
                    foreach ($events as $event) {
                        $routeUrl = route('events.show', $event->slug ?: $event->id);
                        $this->addUrl($urls, $routeUrl, $event->updated_at?->toAtomString());
                    }
                });
        }

        Insight::query()
            ->select(['id', 'slug', 'updated_at'])
            ->published()
            ->where(function ($query): void {
                $query->category('blogs')->orWhere(function ($query): void {
                    $query->category('news');
                });
            })
            ->chunkById(self::CHUNK_SIZE, function ($insights) use (&$urls): void {
                foreach ($insights as $insight) {
                    $routeUrl = route('insights.show', $insight->slug);
                    $this->addUrl($urls, $routeUrl, $insight->updated_at?->toAtomString());
                }
            });

        return view('sitemap', ['urls' => array_values($urls)])->render();
    }

    private function staticPages(): array
    {
        return [
            ['home', HomepageSeoSettings::class],
            ['our-story', OurStorySeoSettings::class],
            ['leadership', LeadershipSeoSettings::class],
            ['accreditations', AccreditationsSeoSettings::class],
            ['csr', CsrSeoSettings::class],
            ['privacy-policy', PrivacySeoSettings::class],
            ['terms-of-use', TermsSeoSettings::class],
            ['media-gallery', MediaGallerySeoSettings::class],
            ['global-partners', GlobalPartnersSeoSettings::class],
            ['pathway-programs', PathwayProgramsSeoSettings::class],
            ['global-opportunities', GlobalOpportunitiesSeoSettings::class],
            ['global-bachelors-pathway', GbpSeoSettings::class],
            ['masters-pathways', MpSeoSettings::class],
            ['edutainment', EdutainmentSeoSettings::class],
            ['dual-mba', DualMbaSeoSettings::class],
            ['mba-masters-landing', MbaMastersSeoSettings::class],
            ['contact', ContactSeoSettings::class],
            ['blogs.index', null],
            ['news.index', null],
            ['programs.index', ProgramsListingSeoSettings::class],
            ['events', EventsSeoSettings::class],
            ['student-success', StudentSuccessSeoSettings::class],
        ];
    }

    private function addUrl(array &$urls, string $loc, ?string $lastmod): void
    {
        $urls[$loc] = [
            'loc' => $loc,
            'lastmod' => $lastmod,
        ];
    }

    private function hasNoindex(?string $robots): bool
    {
        return stripos($robots ?? '', 'noindex') !== false;
    }

    private function canonicalUrl(?string $canonicalUrl, string $fallback): string
    {
        $canonicalUrl = trim((string) $canonicalUrl);
        $canonicalHost = parse_url($canonicalUrl, PHP_URL_HOST);
        $scheme = parse_url($canonicalUrl, PHP_URL_SCHEME);
        $appHost = parse_url((string) config('app.url'), PHP_URL_HOST);
        $appHost = is_string($appHost) && $appHost !== '' ? $appHost : request()->getHost();

        if (! is_string($canonicalHost)
            || ! is_string($scheme)
            || ! in_array(strtolower($scheme), ['http', 'https'], true)
            || strcasecmp(rtrim($canonicalHost, '.'), rtrim($appHost, '.')) !== 0) {
            return $fallback;
        }

        return $canonicalUrl;
    }
}
