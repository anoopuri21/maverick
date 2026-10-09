<?php

namespace Database\Seeders;

use App\Models\Program;
use App\Models\ProgramCategory;
use App\Models\UniversityPartner;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeds Doctorate and Diploma programmes from the generated catalogue data
 * files. Mirrors MasterProgramsSeeder, with two additions: FAQs are seeded,
 * and gcc_heading comes from the data row instead of being hardcoded.
 *
 * Idempotent — identity is the programme `slug`. Safe to re-run as new
 * content lands; existing rows are updated, never duplicated.
 *
 *   php artisan db:seed --class=CatalogProgramsSeeder
 *   php artisan db:seed --class=CatalogProgramsSeeder --force   (production)
 *
 * Data files are generated. Do not hand-edit them:
 *   python3 tools/build_catalog_data.py
 */
class CatalogProgramsSeeder extends Seeder
{
    /** Catalogue data files to load, in order. */
    private const SOURCES = [
        'doctorate_programs.php',
        'diploma_programs.php',
    ];

    public function run(): void
    {
        foreach (self::SOURCES as $source) {
            $path = __DIR__.'/data/'.$source;

            if (! is_file($path)) {
                $this->command?->warn("Skipping missing catalogue file: {$source}");

                continue;
            }

            $this->command?->info("Loading {$source}");
            $this->seedFile(require $path);
        }
    }

    /**
     * @param  array{categories: array, universities: array, programs: array}  $data
     */
    private function seedFile(array $data): void
    {
        DB::transaction(function () use ($data) {
            $categories = $this->categories($data['categories'] ?? []);
            $universities = $this->universities($data['universities'] ?? []);

            foreach ($data['programs'] ?? [] as $row) {
                $this->program($row, $categories, $universities);
            }
        });
    }

    /** @return array<string, ProgramCategory> */
    private function categories(array $rows): array
    {
        $out = [];

        foreach ($rows as $row) {
            $out[$row['slug']] = ProgramCategory::firstOrCreate(
                ['slug' => $row['slug']],
                [
                    'name' => $row['name'],
                    'icon' => 'graduation-cap',
                    'description' => $row['name'].' programmes.',
                    'is_active' => true,
                    'sort_order' => $row['sort_order'],
                ]
            );
        }

        return $out;
    }

    /** @return array<string, UniversityPartner> */
    private function universities(array $rows): array
    {
        $out = [];

        foreach ($rows as $row) {
            $out[$row['slug']] = UniversityPartner::firstOrCreate(
                ['slug' => $row['slug']],
                [
                    'name' => $row['name'],
                    'country' => $row['country'],
                    'country_code' => $row['country_code'],
                    'is_active' => true,
                    'sort_order' => $row['sort_order'],
                ]
            );
        }

        return $out;
    }

    /**
     * @param  array<string, ProgramCategory>  $categories
     * @param  array<string, UniversityPartner>  $universities
     */
    private function program(array $row, array $categories, array $universities): void
    {
        $category = $categories[$row['category_slug']]
            ?? ProgramCategory::query()->where('slug', $row['category_slug'])->first();

        $university = $universities[$row['university_slug']]
            ?? UniversityPartner::query()->where('slug', $row['university_slug'])->first();

        if (! $category || ! $university) {
            $this->command?->error(
                "Skipped {$row['slug']}: missing ".
                (! $category ? "category '{$row['category_slug']}'" : "university '{$row['university_slug']}'")
            );

            return;
        }

        $program = Program::firstOrNew(['slug' => $row['slug']]);
        $creating = ! $program->exists;

        $program->fill([
            'program_category_id' => $category->id,
            'university_partner_id' => $university->id,
            'title' => $row['title'],
            'duration' => $row['duration'],
            'level' => $row['level'],
            'short_description' => $row['hero'],
            'description' => $this->paragraphs($row['overview']),
            'sort_order' => $row['sort_order'],
            'highlights' => $this->pairs($row['highlights'], 'label', 'value'),
            'snapshot' => $this->pairs($row['snapshot'], 'label', 'value'),
            'benefits' => array_map(fn (array $benefit) => [
                'title' => $benefit[0],
                'desc' => $this->richParagraph($benefit[1]),
                'icon' => $benefit[2],
            ], $row['benefits']),
            'learning' => array_map(fn (string $item) => ['item' => $item], $row['learning']),
            'careers' => array_map(fn (string $title) => ['title' => $title], $row['careers']),
            'structure' => array_map(fn (array $stage) => [
                'title' => $stage['title'],
                'subtitle' => $stage['subtitle'] ?? '',
                'modules' => array_map(
                    fn (string $module) => ['title' => $module],
                    $stage['modules']
                ),
            ], $row['structure']),
            'support' => array_map(fn (string $item) => ['item' => $item], $row['support']),
            'gcc_heading' => $row['gcc_heading'] ?? 'Why GCC Students Choose This Programme',
            'gcc_reasons' => $row['gcc'] ?? [],
            'fees' => array_map(fn (string $title) => ['title' => $title], $row['fees']),
        ]);

        // Only set on create, so editorial toggles made in the admin panel
        // survive a re-seed.
        if ($creating) {
            $program->is_active = $row['is_active'] ?? false;
            $program->is_featured = false;
            $program->testimonials = [];
            $program->reviews = [];
        }

        $program->save();

        $program->seo()->updateOrCreate([], [
            'meta_title' => $row['meta_title'],
            'meta_description' => $row['meta_description'],
            'canonical_url' => null,
        ]);

        foreach ($row['faqs'] ?? [] as [$question, $answer, $sort]) {
            $program->faqs()->updateOrCreate(
                ['question' => $question],
                ['answer' => $answer, 'sort_order' => $sort, 'is_active' => true]
            );
        }

        $verb = $creating ? 'Created' : 'Updated';
        $this->command?->info("  {$verb}: {$program->title}");
    }

    /**
     * @param  list<string>  $paragraphs
     */
    private function paragraphs(array $paragraphs): string
    {
        return collect($paragraphs)
            ->map(fn (string $paragraph) => '<p>'.$paragraph.'</p>')
            ->implode('');
    }

    /**
     * Benefit copy is sometimes already a <p> block in the generated data.
     * Wrap plain text only, so those rows are not stored as nested paragraphs.
     */
    private function richParagraph(string $text): string
    {
        $text = trim($text);

        if ($text === '') {
            return '';
        }

        if (strip_tags($text) !== $text) {
            return $text;
        }

        return '<p>'.$text.'</p>';
    }

    /**
     * @param  list<array{0: string, 1: string}>  $rows
     * @return list<array<string, string>>
     */
    private function pairs(array $rows, string $left, string $right): array
    {
        return array_map(fn (array $row) => [$left => $row[0], $right => $row[1]], $rows);
    }
}
