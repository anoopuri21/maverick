<?php

namespace App\Http\Controllers;

use App\Models\Insight;
use App\Settings\NewsHeroSettings;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        $featured = Insight::published()->featuredIn('news')->latest('published_at')->first();

        $articles = Insight::published()->category('news')
            ->when($featured, fn($q) => $q->where('id', '!=', $featured->id))
            ->latest('published_at')
            ->paginate(10);

        $ticker = Insight::published()->category('news')
            ->latest('published_at')
            ->take(5)
            ->get();

        // Hero settings (Admin Panel managed via Spatie Settings)
        $newsHero = app(NewsHeroSettings::class);

        // Top 10 tags from published news items
        $allTags = Insight::published()->category('news')->pluck('tags')->flatten()->filter()->values();
        $topTags = $allTags->countBy()->sortDesc()->take(10)->keys();

        return view('news.index', compact('featured', 'articles', 'ticker', 'newsHero', 'topTags'));
    }

    public function show(Insight $slug)
    {
        $moreUpdates = Insight::published()->category('news')
            ->where('id', '!=', $slug->id)
            ->latest('published_at')
            ->take(5)
            ->get();

        return view('news.show', ['article' => $slug, 'moreUpdates' => $moreUpdates]);
    }
}
