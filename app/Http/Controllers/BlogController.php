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
            'id', 'title', 'slug', 'excerpt', 'featured_image_url', 'featured_image_alt',
            'published_at', 'reading_time_minutes', 'categories', 'tags',
            'author_name', 'author_avatar_url', 'is_featured',
        ];

        $featuredPost = null;
        if ($searchQuery === '') {
            $featuredPost = Insight::published()->featuredIn('blogs')->latest('published_at')->first($listingColumns);
        }

        $query = Insight::published()->category('blogs')->select($listingColumns);

        if ($featuredPost) {
            $query->where('id', '!=', $featuredPost->id);
        }

        if ($searchQuery !== '') {
            $query->where(function ($q) use ($searchQuery) {
                $q->where('title', 'like', "%{$searchQuery}%")
                    ->orWhere('excerpt', 'like', "%{$searchQuery}%");
            });
        }

        $paginatedPosts = $query->latest('published_at')->paginate(10)->withQueryString();

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

    /**
     * Display the specified resource.
     */
    public function show(Insight $blogPost)
    {
        $headings = [];
        $contentWithAnchors = $blogPost->content ?? '';

        if (is_string($contentWithAnchors) && $contentWithAnchors !== '') {
            preg_match_all('/<h([2-3])>(.*?)<\/h[2-3]>/', $contentWithAnchors, $matches, PREG_SET_ORDER);
            foreach ($matches as $match) {
                $level = (int) $match[1];
                $text = strip_tags($match[2]);
                $anchor = strtolower(preg_replace('/[^a-z0-9\-]+/i', '-', $text));
                $headings[] = (object) [
                    'level' => $level,
                    'text' => $text,
                    'anchor' => $anchor,
                ];
            }

            foreach ($headings as $heading) {
                $tag = 'h'.$heading->level;
                $pattern = '/<'.$tag.'>(.*?'.preg_quote($heading->text, '/').'.*?)<\/'.$tag.'>/';
                $replacement = '<'.$tag.' id="'.$heading->anchor.'">$1</'.$tag.'>';
                $contentWithAnchors = preg_replace($pattern, $replacement, $contentWithAnchors, 1);
            }
        }
        $blogPost->content = $contentWithAnchors;

        $relatedPosts = Insight::published()->category('blogs')
            ->where('id', '!=', $blogPost->id)
            ->latest('published_at')
            ->take(3)
            ->get(['id', 'title', 'slug', 'excerpt', 'featured_image_url', 'published_at', 'reading_time_minutes', 'categories', 'tags']);

        return view('blogs.show', ['post' => $blogPost, 'headings' => $headings, 'relatedPosts' => $relatedPosts]);
    }
}
