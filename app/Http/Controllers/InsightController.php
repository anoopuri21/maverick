<?php

namespace App\Http\Controllers;

use App\Models\Insight;
use Illuminate\Http\Request;

class InsightController extends Controller
{
    public function show(Insight $slug)
    {
        $slug->load('featuredImageAsset:id,url,alt');

        if ($slug->hasCategory('news')) {
            $slug->content = lazy_load_rich_content_images(normalize_rich_html_media($slug->content));

            $moreUpdates = Insight::published()->category('news')
                ->where('id', '!=', $slug->id)
                ->latest('published_at')
                ->latest('id')
                ->take(5)
                ->get([
                    'id', 'title', 'slug', 'excerpt', 'published_at',
                    'reading_time_minutes', 'author_name',
                ]);

            return view('news.show', [
                'article' => $slug,
                'moreUpdates' => $moreUpdates ?? collect(),
            ]);
        }

        // Default: blog-style detail view (covers items tagged
        // only "blogs", or any other/future category without a
        // dedicated detail template yet)
        $headings = [];
        $contentWithAnchors = $slug->content ?? '';

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

            // Inject matching IDs into the H2/H3 tags so the table of contents can anchor to them.
            foreach ($headings as $heading) {
                $tag = 'h'.$heading->level;
                $pattern = '/<'.$tag.'>(.*?'.preg_quote($heading->text, '/').'.*?)<\/'.$tag.'>/';
                $replacement = '<'.$tag.' id="'.$heading->anchor.'">$1</'.$tag.'>';
                $contentWithAnchors = preg_replace($pattern, $replacement, $contentWithAnchors, 1);
            }
        }
        $slug->content = lazy_load_rich_content_images(normalize_rich_html_media($contentWithAnchors));

        $relatedPosts = Insight::published()->category('blogs')
            ->with('featuredImageAsset:id,url,alt')
            ->where('id', '!=', $slug->id)
            ->latest('published_at')
            ->latest('id')
            ->take(3)
            ->get([
                'id', 'title', 'slug', 'excerpt', 'featured_image_url',
                'featured_image_url_asset_id', 'featured_image_alt', 'published_at',
                'reading_time_minutes', 'author_name', 'author_avatar_url',
            ]);

        return view('blogs.show', [
            'post' => $slug,
            'headings' => $headings ?? [],
            'relatedPosts' => $relatedPosts ?? collect(),
        ]);
    }
}
