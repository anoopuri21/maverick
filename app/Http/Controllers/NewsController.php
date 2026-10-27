<?php

namespace App\Http\Controllers;

use App\Models\Insight;
use App\Settings\NewsHeroSettings;
use App\Support\PublicContentCache;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $listingColumns = [
            'id', 'title', 'slug', 'excerpt', 'featured_image_url',
            'featured_image_url_asset_id', 'featured_image_alt',
            'published_at', 'reading_time_minutes', 'author_name', 'author_avatar_url',
        ];

        $isAutoLoadRequest = $request->expectsJson();
        if ($isAutoLoadRequest) {
            // The partial response only needs the featured ID for exclusion; skip
            // loading its full model and image relation on each scroll request.
            $featuredId = Insight::published()->featuredIn('news')
                ->latest('published_at')
                ->latest('id')
                ->value('id');
            $featured = null;
        } else {
            $featured = Insight::published()->featuredIn('news')
                ->with('featuredImageAsset:id,url,alt')
                ->latest('published_at')
                ->latest('id')
                ->first($listingColumns);
            $featuredId = $featured?->id;
        }

        // Keep the public feed to ten rows per request without a full COUNT query.
        $articles = Insight::published()->category('news')
            ->with('featuredImageAsset:id,url,alt')
            ->select($listingColumns)
            ->when($featuredId, fn ($q) => $q->where('id', '!=', $featuredId))
            ->latest('published_at')
            ->latest('id')
            ->simplePaginate(10)
            ->withQueryString();

        // The browser requests only the next batch of cards while scrolling; do
        // this before loading page-only ticker, tag, and hero settings data.
        if ($isAutoLoadRequest) {
            return response()->json([
                'html' => view('news.partials.article-cards', compact('articles'))->render(),
                'count' => $articles->count(),
                'current_page' => $articles->currentPage(),
                'next_url' => $articles->nextPageUrl(),
                'has_more' => $articles->hasMorePages(),
            ]);
        }

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
