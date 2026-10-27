<?php

namespace App\Http\Controllers;

use App\Models\Insight;
use App\Settings\NewsHeroSettings;
use App\Support\PublicContentCache;

class NewsController extends Controller
{
    public function index()
    {
        $listingColumns = [
            'id', 'title', 'slug', 'excerpt', 'featured_image_url', 'featured_image_alt',
            'published_at', 'reading_time_minutes', 'categories', 'tags',
            'author_name', 'author_avatar_url', 'is_featured',
        ];

        $featured = Insight::published()->featuredIn('news')->latest('published_at')->first($listingColumns);

        // Keep the public feed to ten rows per request without a full COUNT query.
        $articles = Insight::published()->category('news')
            ->select($listingColumns)
            ->when($featured, fn ($q) => $q->where('id', '!=', $featured->id))
            ->latest('published_at')
            ->latest('id')
            ->simplePaginate(10)
            ->withQueryString();

        $ticker = Insight::published()->category('news')
            ->latest('published_at')
            ->take(5)
            ->get(['id', 'title', 'slug', 'published_at']);

        $newsHero = safe_settings(NewsHeroSettings::class);

        $topTags = collect(PublicContentCache::remember(PublicContentCache::NEWS_TOP_TAGS, function () {
            return Insight::published()->category('news')
                ->whereNotNull('tags')
                ->pluck('tags')
                ->map(fn ($t) => is_array($t) ? $t : [])
                ->flatten()
                ->filter()
                ->countBy()
                ->sortDesc()
                ->take(10)
                ->keys()
                ->values()
                ->all();
        }));

        return view('news.index', compact('featured', 'articles', 'ticker', 'newsHero', 'topTags'));
    }
}
