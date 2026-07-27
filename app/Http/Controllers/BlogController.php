<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = collect(['All'])->merge(
            BlogPost::published()->distinct()->orderBy('category')->pluck('category')
        );

        $activeCategory = $request->query('category', 'All');
        $searchQuery = trim((string) $request->query('search', ''));

        $query = BlogPost::published();

        if ($activeCategory !== 'All') {
            $query->where('category', $activeCategory);
        }

        if ($searchQuery !== '') {
            $query->where(function ($q) use ($searchQuery) {
                $q->where('title', 'like', "%{$searchQuery}%")
                    ->orWhere('excerpt', 'like', "%{$searchQuery}%")
                    ->orWhere('category', 'like', "%{$searchQuery}%");
            });
        }

        // The featured post is only ever highlighted when browsing the
        // unfiltered "All" list with no active search, and is excluded
        // from the regular grid so it isn't shown twice.
        $featuredPost = null;
        if ($activeCategory === 'All' && $searchQuery === '') {
            $featuredPost = BlogPost::published()->featured()->latest('published_at')->first();
        }

        if ($featuredPost) {
            $query->where('id', '!=', $featuredPost->id);
        }

        $paginatedPosts = $query->latest('published_at')->paginate(9)->withQueryString();

        return view('blogs.index', compact('paginatedPosts', 'featuredPost', 'categories', 'activeCategory', 'searchQuery'));
    }

    /**
     * Display the specified resource.
     */
    public function show(BlogPost $blogPost)
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

        $relatedPosts = BlogPost::published()
            ->where('category', $blogPost->category)
            ->where('id', '!=', $blogPost->id)
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('blogs.show', ['post' => $blogPost, 'headings' => $headings, 'relatedPosts' => $relatedPosts]);
    }
}
