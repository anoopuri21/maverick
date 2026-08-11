<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\ProgramCategory;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    /**
     * Programme listing page.
     */
    public function index()
    {
        $categories = ProgramCategory::where('is_active', true)->orderBy('name')->get();

        $programs = Program::select([
                'id', 'program_category_id', 'title', 'slug', 'partner_university',
                'duration', 'level', 'short_description', 'description', 'image_url', 'sort_order',
            ])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get();

        return view('pages.programs.index', compact('categories', 'programs'));
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

        $relatedPrograms = Program::select(['id', 'title', 'slug', 'partner_university', 'duration', 'short_description', 'image_url'])
            ->where('is_active', true)
            ->where('id', '!=', $program->id)
            ->where(function ($q) use ($program) {
                if ($program->program_category_id) {
                    $q->where('program_category_id', $program->program_category_id);
                }
            })
            ->orderBy('sort_order')
            ->limit(3)
            ->get();

        return view('pages.programs.detail', compact('program', 'relatedPrograms'));
    }
}
