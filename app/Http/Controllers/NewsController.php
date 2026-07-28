<?php

namespace App\Http\Controllers;

use App\Models\Insight;
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

        return view('news.index', compact('featured', 'articles', 'ticker'));
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
