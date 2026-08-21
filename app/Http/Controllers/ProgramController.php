<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\ProgramCategory;
use App\Settings\ProgramsListingSeoSettings;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    /**
     * Programme listing page.
     */
    public function index()
    {
        $categories = ProgramCategory::withCount([
                'programs' => fn ($q) => $q->where('is_active', true),
            ])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $programs = Program::select([
                'id', 'program_category_id', 'university_partner_id', 'title', 'slug',
                'duration', 'level', 'short_description', 'description', 'image_url', 'sort_order',
            ])
            ->with(['programCategory', 'universityPartner'])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get();

        return view('pages.programs.index', [
            'categories' => $categories,
            'programs' => $programs,
            'programsListingSeo' => app(ProgramsListingSeoSettings::class),
        ]);
    }

    /**
     * Programme detail page.
     */
    public function show(Request $request, $slug)
    {
        $program = Program::where('slug', $slug)
            ->where('is_active', true)
            ->with(['programCategory', 'faqs' => fn ($q) => $q->where('is_active', true), 'seo'])
            ->first();

        if (! $program) {
            abort(404);
        }

        return view('pages.programs.detail', compact('program'));
    }
}
