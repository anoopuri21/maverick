<?php

namespace App\Http\Controllers;

use App\Models\Insight;
use App\Settings\BlogHeroSettings;
use Illuminate\Http\Request;

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

        // The featured post is only ever highlighted when browsing the
        // unfiltered list with no active search, and is excluded
        // from the regular grid so it isn't shown twice.
        $featuredPost = null;
        if ($searchQuery === '') {
            $featuredPost = Insight::published()->featuredIn('blogs')->latest('published_at')->first();
        }

        $query = Insight::published()->category('blogs');

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

        // Hero settings (Admin Panel managed via Spatie Settings)
        $blogHero = app(BlogHeroSettings::class);

        // Top 10 tags from published blog insights
        $allTags = Insight::published()->category('blogs')->pluck('tags')->flatten()->filter()->values();
        $topTags = $allTags->countBy()->sortDesc()->take(10)->keys();

        return view('blogs.index', compact('paginatedPosts', 'featuredPost', 'categories', 'activeCategory', 'searchQuery', 'blogHero', 'topTags'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Insight $blogPost)
    {
        // Dynamic Table of Contents: scan the content HTML for <h2>/<h3> tags.
        preg_match_all('/<h([2-3])>(.*?)<\/h[2-3]>/', $blogPost->content, $matches, PREG_SET_ORDER);
        $headings = [];
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

        // Inject matching IDs into the H2/H3 tags so the table of contents can anchor to them.
        $contentWithAnchors = $blogPost->content;
        foreach ($headings as $heading) {
            $tag = 'h' . $heading->level;
            $pattern = '/<' . $tag . '>(.*?' . preg_quote($heading->text, '/') . '.*?)<\/' . $tag . '>/';
            $replacement = '<' . $tag . ' id="' . $heading->anchor . '">$1</' . $tag . '>';
            $contentWithAnchors = preg_replace($pattern, $replacement, $contentWithAnchors, 1);
        }
        $blogPost->content = $contentWithAnchors;

        $relatedPosts = Insight::published()->category('blogs')
            ->where('id', '!=', $blogPost->id)
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('blogs.show', ['post' => $blogPost, 'headings' => $headings, 'relatedPosts' => $relatedPosts]);
    }
}
