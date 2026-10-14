<?php

namespace Database\Seeders;

use App\Models\Program;
use App\Models\ProgramCategory;
use App\Models\UniversityPartner;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeds Doctorate, Diploma and source-verified Bachelors programmes from the
 * generated catalogue data files. Mirrors MasterProgramsSeeder; FAQs are seeded,
 * and gcc_heading comes from the data row instead of being hardcoded.
 *
 * Idempotent — identity is the programme `slug`. Existing rows are updated,
 * never duplicated. For staging without creating categories/partners, use
 * BachelorsProgramsSeeder (five new pages only) or
 * ExistingCatalogProgramsSeeder (all 67 catalogue pages) instead.
 *
 * This legacy seeder can still create Doctorate/Diploma categories or partners
 * when bootstrapping a new database. Bachelors always uses existing records.
 * Data files are generated; never edit them by hand:
 *   python3 tools/build_catalog_data.py
 */
class CatalogProgramsSeeder extends Seeder
{
    /** Catalogue data files to load, in order. */
    private const SOURCES = [
        'doctorate_programs.php',
        'diploma_programs.php',
        'bachelors_programs.php',
    ];

    /** @return list<string> */
    protected function sources(): array
    {
        return self::SOURCES;
    }

    /** Safe seeder variants require every declared category and partner to exist. */
    protected function existingReferencesOnly(): bool
    {
        return false;
    }

    public function run(): void
    {
        // Read every required file before touching the database. A missing or
        // malformed deployment must fail, not report success with fewer pages.
        $files = [];
        foreach ($this->sources() as $source) {
            $path = __DIR__.'/data/'.$source;
            if (! is_file($path)) {
                throw new \RuntimeException("Missing catalogue data file: {$path}");
            }

            $data = require $path;
            if (! is_array($data) || ! is_array($data['categories'] ?? null)
                || ! is_array($data['universities'] ?? null)
                || ! is_array($data['programs'] ?? null) || $data['programs'] === []) {
                throw new \RuntimeException("Invalid or empty catalogue data file: {$path}");
            }

            $files[$source] = $data;
        }

        // One transaction covers every file: an absent Bachelor partner, an
        // unexpected slug collision or a failed FAQ must roll back ALL rows.
        DB::transaction(function () use ($files) {
            $references = [];
            foreach ($files as $source => $data) {
                // The legacy bootstrap may create Doctorate/Diploma references;
                // the two safe commands never create any, and Bachelors never do.
                $existingOnly = $this->existingReferencesOnly()
                    || $source === 'bachelors_programs.php';
                $references[$source] = [
                    $this->categories($data['categories'], $existingOnly),
                    $this->universities($data['universities'], $existingOnly),
                ];
            }

            foreach ($files as $source => $data) {
                $this->command?->info("Loading {$source}");
                [$categories, $universities] = $references[$source];
                foreach ($data['programs'] as $row) {
                    $this->program($row, $categories, $universities);
                }
            }
        });
    }

    /** @return array<string, ProgramCategory> */
    private function categories(array $rows, bool $existingOnly): array
    {
        $out = [];

        foreach ($rows as $row) {
            if ($existingOnly) {
                $existing = ProgramCategory::query()->where('slug', $row['slug'])->first();

                if (! $existing) {
                    throw new \RuntimeException("Existing programme category required: {$row['slug']}");
                }

                $out[$row['slug']] = $existing;

                continue;
            }

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
    private function universities(array $rows, bool $existingOnly): array
    {
        $out = [];

        foreach ($rows as $row) {
            if ($existingOnly) {
                $existing = UniversityPartner::query()->where('slug', $row['slug'])->first();

                if (! $existing) {
                    throw new \RuntimeException("Existing university partner required: {$row['slug']}");
                }

                $out[$row['slug']] = $existing;

                continue;
            }

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
        $category = $categories[$row['category_slug']] ?? null;
        $university = $universities[$row['university_slug']] ?? null;
        if (! $category || ! $university) {
            throw new \RuntimeException("Undeclared category or partner for programme: {$row['slug']}");
        }

        $program = Program::firstOrNew(['slug' => $row['slug']]);
        $creating = ! $program->exists;
        if (! $creating && ((int) $program->program_category_id !== (int) $category->id
            || (int) $program->university_partner_id !== (int) $university->id)) {
            throw new \RuntimeException("Refusing to reassign existing programme: {$row['slug']}");
        }

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
