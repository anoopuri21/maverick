<?php

namespace App\Http\Controllers;

use App\Models\Insight;
use Illuminate\Http\Request;

class InsightController extends Controller
{
    public function show(Insight $slug)
    {
        if ($slug->hasCategory('news')) {
            $moreUpdates = Insight::published()->category('news')
                ->where('id', '!=', $slug->id)
                ->latest('published_at')
                ->take(5)
                ->get();

            return view('news.show', ['article' => $slug, 'moreUpdates' => $moreUpdates]);
        }

        // Default: blog-style detail view (covers items tagged
        // only "blogs", or any other/future category without a
        // dedicated detail template yet)
        preg_match_all('/<h([2-3])>(.*?)<\/h[2-3]>/', $slug->content, $matches, PREG_SET_ORDER);
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
        $contentWithAnchors = $slug->content;
        foreach ($headings as $heading) {
            $tag = 'h' . $heading->level;
            $pattern = '/<' . $tag . '>(.*?' . preg_quote($heading->text, '/') . '.*?)<\/' . $tag . '>/';
            $replacement = '<' . $tag . ' id="' . $heading->anchor . '">$1</' . $tag . '>';
            $contentWithAnchors = preg_replace($pattern, $replacement, $contentWithAnchors, 1);
        }
        $slug->content = $contentWithAnchors;

        $relatedPosts = Insight::published()->category('blogs')
            ->where('id', '!=', $slug->id)
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('blogs.show', ['post' => $slug, 'headings' => $headings, 'relatedPosts' => $relatedPosts]);
    }
}
