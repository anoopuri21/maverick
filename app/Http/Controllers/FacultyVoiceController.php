<?php

namespace App\Http\Controllers;

use App\Models\FacultyInsight;
use App\Settings\FacultyVoiceSeoSettings;
use App\Settings\FacultyVoicePageSettings;
use Illuminate\Http\Request;

class FacultyVoiceController extends Controller
{
    public function index()
    {
        $cardColumns = [
            'id', 'title', 'slug', 'badge', 'excerpt', 'faculty_name', 'faculty_role',
            'image_url', 'hero_image_url', 'faculty_avatar_url', 'link_url',
            'published_at', 'reading_time_minutes', 'sort_order',
        ];

        $voices = FacultyInsight::published()
            ->select($cardColumns)
            ->hasPublicSlug()
            ->orderBy('sort_order')
            ->orderByDesc('published_at')
            ->paginate(9);

        return view('pages.faculty-voice.index', [
            'facultyVoicePage' => safe_settings(FacultyVoicePageSettings::class),
            'voices' => $voices,
            'facultyVoiceSeo' => safe_settings(FacultyVoiceSeoSettings::class),
        ]);
    }

    public function show(Request $request, string $slug)
    {
        $insight = FacultyInsight::published()
            ->where('slug', $slug)
            ->firstOrFail();

        $cardColumns = [
            'id', 'title', 'slug', 'badge', 'excerpt', 'faculty_name', 'faculty_role',
            'image_url', 'hero_image_url', 'faculty_avatar_url', 'link_url',
            'published_at', 'reading_time_minutes', 'sort_order',
        ];

        $relatedQuery = FacultyInsight::published()
            ->select($cardColumns)
            ->where('id', '!=', $insight->id);

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
