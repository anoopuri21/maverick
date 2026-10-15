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

    /** @return array<string, int> Expected programme count by source file. */
    protected function expectedProgramCounts(): array
    {
        return [];
    }

    /** Optional existing-only copy refreshes run in the same transaction. */
    protected function afterCatalogProgramsSeeded(array $files): void
    {
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

            $expected = $this->expectedProgramCounts()[$source] ?? null;
            if ($expected !== null && count($data['programs']) !== $expected) {
                throw new \RuntimeException("Expected {$expected} programmes in {$source}; found ".count($data['programs']));
            }

            // Source approval is an offline gate; at runtime, fail closed if
            // any generated row would auto-publish on first insertion. Existing
            // programmes keep their admin-set activation during a re-seed.
            foreach ($data['programs'] as $row) {
                if (! is_array($row) || ($row['is_active'] ?? null) !== false) {
                    $slug = is_array($row) ? ($row['slug'] ?? $source) : $source;
                    throw new \RuntimeException("Catalogue programme must default inactive: {$slug}");
                }
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

            $this->afterCatalogProgramsSeeded($files);
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
        if (! $creating && $this->existingReferencesOnly()
            && ($program->title !== $row['title'] || $program->level !== $row['level'])) {
            throw new \RuntimeException("Refusing to change existing programme identity: {$row['slug']}");
        }

        $fields = [
            'program_category_id' => $category->id,
            'university_partner_id' => $university->id,
            'title' => $row['title'],
            'duration' => $row['duration'],
            'level' => $row['level'],
            'short_description' => $row['hero'],
            'description' => $this->paragraphs($row['overview']),
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
        ];

        // The safe existing-reference commands must not change a programme's
        // identity or admin ordering. These fields are preflight-checked above.
        if (! $creating && $this->existingReferencesOnly()) {
            unset($fields['program_category_id'], $fields['university_partner_id'], $fields['title'], $fields['level']);
        } else {
            $fields['sort_order'] = $row['sort_order'];
        }
        $program->fill($fields);

        // Only set on create, so editorial toggles made in the admin panel
        // survive a re-seed.
        if ($creating) {
            $program->is_active = $row['is_active'] ?? false;
            $program->is_featured = false;
            $program->testimonials = [];
            $program->reviews = [];
        }

        $program->save();

        // Update the authored SEO copy without erasing a canonical URL or
        // other SEO fields set in the admin panel.
        $seo = $program->seo()->firstOrNew([]);
        $seo->fill([
            'meta_title' => $row['meta_title'],
            'meta_description' => $row['meta_description'],
        ]);
        $seo->save();

        foreach ($row['faqs'] ?? [] as [$question, $answer, $sort]) {
            $this->syncFaq($program, $question, $answer, $sort);
        }

        $verb = $creating ? 'Created' : 'Updated';
        $this->command?->info("  {$verb}: {$program->title}");
    }

    /**
     * Reconcile a changed question by its original numbered slot instead of
     * inserting a duplicate FAQ. Ambiguous/admin-duplicated slots abort the
     * transaction rather than overwriting an arbitrary record.
     */
    private function syncFaq(Program $program, string $question, string $answer, int $sort): void
    {
        $atSort = $program->faqs()->where('sort_order', $sort)->get();
        if ($atSort->count() > 1) {
            throw new \RuntimeException("Ambiguous FAQ slot {$sort} for programme: {$program->slug}");
        }

        $byQuestion = $program->faqs()->where('question', $question)->first();
        if ($byQuestion && $atSort->isNotEmpty() && $byQuestion->id !== $atSort->first()->id) {
            throw new \RuntimeException("Conflicting FAQ question and slot {$sort} for programme: {$program->slug}");
        }

        $faq = $byQuestion ?? $atSort->first() ?? $program->faqs()->make();
        $faq->question = $question;
        $faq->answer = $answer;
        // Keep admin ordering if a matching FAQ was deliberately moved.
        if (! $faq->exists || $atSort->isNotEmpty()) {
            $faq->sort_order = $sort;
        }
        if (! $faq->exists) {
            $faq->is_active = true;
        }
        $faq->save();
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
