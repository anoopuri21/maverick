<?php

namespace Tests\Feature;

use App\Models\Program;
use App\Models\ProgramCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProgramSnapshotNoteTest extends TestCase
{
    use RefreshDatabase;

    public function test_bachelors_program_shows_duration_note_in_snapshot_card(): void
    {
        $program = $this->createProgramInCategory(
            'bachelors-snapshot-note',
            'Bachelors',
            ProgramCategory::BACHELORS_SLUG
        );

        $response = $this->get(route('programs.show', $program->slug));

        $response->assertOk();
        $response->assertSee('Duration will be based on your experience');
        $response->assertSee('Online');
    }

    public function test_singular_bachelor_category_slug_shows_duration_note_in_snapshot_card(): void
    {
        $program = $this->createProgramInCategory(
            'bachelor-snapshot-note',
            'Bachelor',
            ProgramCategory::BACHELOR_SLUG
        );

        $response = $this->get(route('programs.show', $program->slug));

        $response->assertOk();
        $response->assertSee('Duration will be based on your experience');
        $response->assertSee('Snapshot study mode');
        $response->assertSee('Online');
    }

    public function test_non_bachelors_programs_hide_duration_note_but_keep_snapshot_rows(): void
    {
        foreach (['mba', 'masters', 'diploma', 'doctorate'] as $categorySlug) {
            $program = $this->createProgramInCategory(
                $categorySlug.'-snapshot-note',
                ucfirst($categorySlug),
                $categorySlug
            );

            $response = $this->get(route('programs.show', $program->slug));

            $response->assertOk();
            $response->assertDontSee('Duration will be based on your experience');
            $response->assertSee('Snapshot study mode');
            $response->assertSee('Online');
        }
    }

    private function createProgramInCategory(string $programSlug, string $categoryName, string $categorySlug): Program
    {
        $category = ProgramCategory::query()->create([
            'name' => $categoryName,
            'slug' => $categorySlug,
            'is_active' => true,
        ]);

        return Program::query()->create([
            'program_category_id' => $category->id,
            'title' => $categoryName.' Snapshot Test Program',
            'slug' => $programSlug,
            'description' => 'A program detail page for snapshot note coverage.',
            'snapshot' => [
                ['label' => 'Snapshot study mode', 'value' => 'Online'],
            ],
            'is_active' => true,
        ]);
    }
}
