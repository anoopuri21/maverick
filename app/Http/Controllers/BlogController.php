<?php

namespace App\Http\Controllers;

use App\Models\Insight;
use App\Settings\BlogHeroSettings;
use Illuminate\Http\Request;
use App\Support\PublicContentCache;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = collect(['All']);
        $activeCategory = 'All';
        $searchQuery = trim((string) $request->query('search', ''));

        $listingColumns = [
            'id', 'title', 'slug', 'excerpt', 'featured_image_url', 'featured_image_url_asset_id',
            'featured_image_alt', 'published_at', 'reading_time_minutes', 'author_name', 'author_avatar_url',
        ];

        $featuredPost = null;
        if ($searchQuery === '') {
            $featuredPost = Insight::published()->featuredIn('blogs')
                ->with('featuredImageAsset:id,url,alt')
                ->latest('published_at')
                ->latest('id')
                ->first($listingColumns);
        }

        $query = Insight::published()->category('blogs')
            ->with('featuredImageAsset:id,url,alt')
            ->select($listingColumns);

        if ($featuredPost) {
            $query->where('id', '!=', $featuredPost->id);
        }

        if ($searchQuery !== '') {
            $query->where(function ($q) use ($searchQuery) {
                $q->where('title', 'like', "%{$searchQuery}%")
                    ->orWhere('excerpt', 'like', "%{$searchQuery}%");
            });
        }

        // simplePaginate avoids a full COUNT query on the public article feed.
        $paginatedPosts = $query->latest('published_at')->latest('id')->simplePaginate(10)->withQueryString();

        $blogHero = safe_settings(BlogHeroSettings::class);

        $topTags = collect(PublicContentCache::remember(PublicContentCache::BLOGS_TOP_TAGS, function () {
            return Insight::published()->category('blogs')
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

        return view('blogs.index', compact('paginatedPosts', 'featuredPost', 'categories', 'activeCategory', 'searchQuery', 'blogHero', 'topTags'));
    }
}
