<?php

namespace App\Http\Controllers;

use App\Models\FacultyInsight;
use App\Settings\FacultyVoiceSeoSettings;
use Illuminate\Http\Request;

class FacultyVoiceController extends Controller
{
    public function index()
    {
        $voices = FacultyInsight::published()
            ->orderBy('sort_order')
            ->orderByDesc('published_at')
            ->paginate(9);

        return view('pages.faculty-voice.index', [
            'voices' => $voices,
            'facultyVoiceSeo' => app(FacultyVoiceSeoSettings::class),
        ]);
    }

    public function show(Request $request, string $slug)
    {
        $insight = FacultyInsight::published()
            ->where('slug', $slug)
            ->firstOrFail();

        $relatedQuery = FacultyInsight::published()->where('id', '!=', $insight->id);

        $related = collect();

        if (filled($insight->badge)) {
            $related = (clone $relatedQuery)
                ->where('badge', $insight->badge)
                ->orderBy('sort_order')
                ->orderByDesc('published_at')
                ->limit(4)
                ->get();
        }

        if ($related->isEmpty()) {
            $related = $relatedQuery
                ->orderBy('sort_order')
                ->orderByDesc('published_at')
                ->limit(4)
                ->get();
        } elseif ($related->count() < 4) {
            $filler = $relatedQuery
                ->whereNotIn('id', $related->pluck('id'))
                ->orderBy('sort_order')
                ->orderByDesc('published_at')
                ->limit(4 - $related->count())
                ->get();

            $related = $related->concat($filler);
        }

        $seo = (object) [
            'meta_title' => $insight->meta_title ?: ($insight->title.' | Faculty Voice'),
            'meta_description' => $insight->meta_description ?: $insight->excerpt,
            'meta_keywords' => null,
            'canonical_url' => $request->url(),
            'robots' => 'index, follow',
            'og_title' => $insight->meta_title ?: $insight->title,
            'og_description' => $insight->meta_description ?: $insight->excerpt,
            'og_image_url' => $insight->ogImageUrl(),
            'og_type' => 'article',
            'twitter_card' => 'summary_large_image',
            'twitter_title' => $insight->meta_title ?: $insight->title,
            'twitter_description' => $insight->meta_description ?: $insight->excerpt,
            'twitter_image_url' => $insight->ogImageUrl(),
            'schema_json' => null,
            'custom_head_scripts' => null,
            'custom_body_scripts' => null,
        ];

        return view('pages.faculty-voice.show', [
            'insight' => $insight,
            'relatedVoices' => $related,
            'seo' => $seo,
        ]);
    }
}
