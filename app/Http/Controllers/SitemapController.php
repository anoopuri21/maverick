<?php

namespace App\Http\Controllers;

use App\Models\Insight;
use App\Models\Program;
use Illuminate\Http\Response;
use Throwable;

/**
 * XML sitemap for search engines (Google Search Console / Bing Webmaster).
 *
 * Registered at /sitemap.xml and referenced from public/robots.txt.
 * URLs are generated from the current request host, so the same build works
 * on staging and on the live domain.
 */
class SitemapController extends Controller
{
    /**
     * Static pages: route name => [priority, changefreq].
     */
    private const STATIC_PAGES = [
        'home' => ['1.0', 'weekly'],
        'programs.index' => ['0.9', 'weekly'],
        'mba-masters-landing' => ['0.9', 'monthly'],
        'dual-mba' => ['0.8', 'monthly'],
        'about-us' => ['0.8', 'monthly'],
        'our-story' => ['0.7', 'monthly'],
        'leadership' => ['0.7', 'monthly'],
        'accreditations' => ['0.7', 'monthly'],
        'global-partners' => ['0.7', 'monthly'],
        'pathway-programs' => ['0.7', 'monthly'],
        'global-opportunities' => ['0.7', 'monthly'],
        'global-bachelors-pathway' => ['0.7', 'monthly'],
        'masters-pathways' => ['0.7', 'monthly'],
        'edutainment' => ['0.6', 'monthly'],
        'student-success' => ['0.6', 'monthly'],
        'student-success.stories' => ['0.6', 'monthly'],
        'student-success.videos' => ['0.6', 'monthly'],
        'events' => ['0.6', 'weekly'],
        'blogs.index' => ['0.7', 'weekly'],
        'news.index' => ['0.6', 'weekly'],
        'media-gallery' => ['0.5', 'monthly'],
        'csr' => ['0.5', 'yearly'],
        'contact' => ['0.8', 'yearly'],
        'privacy-policy' => ['0.3', 'yearly'],
        'terms-of-use' => ['0.3', 'yearly'],
    ];

    public function index(): Response
    {
        $entries = collect(self::STATIC_PAGES)
            ->map(fn (array $meta, string $name): array => [
                'loc' => route($name),
                'lastmod' => null,
                'changefreq' => $meta[1],
                'priority' => $meta[0],
            ])
            ->values()
            ->concat($this->programs())
            ->concat($this->insights())
            ->all();

        return response()
            ->view('sitemap.index', ['entries' => $entries])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    /**
     * @return list<array{loc: string, lastmod: ?string, changefreq: string, priority: string}>
     */
    private function programs(): array
    {
        try {
            return Program::query()
                ->where('is_active', true)
                ->hasPublicSlug()
                ->orderBy('title')
                ->get(['id', 'slug', 'updated_at'])
                ->map(fn (Program $program): array => [
                    'loc' => route('programs.show', $program->slug),
                    'lastmod' => $this->lastmod($program->updated_at),
                    'changefreq' => 'weekly',
                    'priority' => '0.8',
                ])
                ->all();
        } catch (Throwable $e) {
            report($e);

            return [];
        }
    }

    /**
     * Blog articles + news updates (both live at /{slug}).
     *
     * @return list<array{loc: string, lastmod: ?string, changefreq: string, priority: string}>
     */
    private function insights(): array
    {
        $entries = [];

        foreach (['blogs' => '0.7', 'news' => '0.6'] as $category => $priority) {
            try {
                $rows = Insight::published()
                    ->category($category)
                    ->latest('published_at')
                    ->get(['id', 'slug', 'published_at', 'updated_at']);

                foreach ($rows as $row) {
                    $entries[] = [
                        'loc' => route('insights.show', $row->slug),
                        'lastmod' => $this->lastmod($row->updated_at ?? $row->published_at),
                        'changefreq' => 'monthly',
                        'priority' => $priority,
                    ];
                }
            } catch (Throwable $e) {
                report($e);
            }
        }

        return $entries;
    }

    private function lastmod(mixed $value): ?string
    {
        if (blank($value)) {
            return null;
        }

        try {
            return \Illuminate\Support\Carbon::parse($value)->toAtomString();
        } catch (Throwable) {
            return null;
        }
    }
}
