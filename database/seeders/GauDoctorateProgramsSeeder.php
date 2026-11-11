<?php

namespace Database\Seeders;

use App\Models\Program;
use App\Models\ProgramCategory;
use App\Models\SeoMetadata;
use App\Models\UniversityPartner;
use App\Support\PublicContentCache;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Publishes the two Girne American University doctorate pages that GAU lists
 * but does not describe: PhD in Psychology and PhD in Law.
 *
 * Content is read from the generated doctorate catalogue file, so the copy has
 * one source of truth and no duplication. Unlike the catalogue seeders, this
 * one is deliberately narrow: it touches only the two slugs below, and it
 * activates them, because both rows were written as "publish now" pages.
 *
 * Idempotent — identity is the programme slug. Re-running updates copy, SEO and
 * FAQs without creating duplicates, and never touches any other programme.
 *
 *   php artisan db:seed --class=GauDoctorateProgramsSeeder
 *   php artisan db:seed --class=GauDoctorateProgramsSeeder --force   (production)
 */
class GauDoctorateProgramsSeeder extends Seeder
{
    private const SOURCE = 'doctorate_gau_programs.php';

    private const SLUGS = [
        'phd-in-psychology-girne-american-university',
        'phd-in-law-girne-american-university',
    ];

    private const CATEGORY = ['slug' => 'doctorate', 'name' => 'Doctorate', 'sort_order' => 200];

    private const UNIVERSITY = [
        'slug' => 'girne-american-university',
        'name' => 'Girne American University',
        'country' => 'North Cyprus',
        'country_code' => 'CY',
        'sort_order' => 3,
    ];

    public function run(): void
    {
        $rows = $this->rows();

        DB::transaction(function () use ($rows) {
            $category = $this->category();
            $university = $this->university();

            // Publishing the pages is not enough if the category or partner is
            // switched off: the detail page still renders, but the listing and
            // navigation skip them. Say so instead of leaving it silent.
            if (! $category->is_active) {
                $this->command?->warn('Doctorate category is inactive: the pages will not appear in the programme listing.');
            }

            if (! $university->is_active) {
                $this->command?->warn('University partner is inactive: the pages will not appear in partner listings.');
            }

            foreach ($rows as $row) {
                $program = $this->program($row, $category, $university);
                $this->seo($program, $row);
                $this->faqs($program, $row['faqs'] ?? []);

                $this->command?->info("Published: {$program->title} → /programs/{$program->slug}");
            }
        });

        // Programme observers already clear these on save; repeat so an
        // idempotent re-run also refreshes the listing cache.
        PublicContentCache::forget(
            PublicContentCache::PROGRAMS_LISTING,
            PublicContentCache::FEATURED_PROGRAMS,
            PublicContentCache::NAVMENU_PROGRAMS,
        );

        $this->command?->info('Sitemap cache: run `php artisan cache:clear` if the sitemap still omits these pages.');
    }

    /**
     * Read the two rows from the generated catalogue file and fail closed if
     * the file or either row is missing.
     *
     * @return list<array<string, mixed>>
     */
    private function rows(): array
    {
        $path = __DIR__.'/data/'.self::SOURCE;

        if (! is_file($path)) {
            throw new \RuntimeException("Missing catalogue data file: {$path}");
        }

        $data = require $path;

        if (! is_array($data['programs'] ?? null)) {
            throw new \RuntimeException("Invalid catalogue data file: {$path}");
        }

        $bySlug = [];

        foreach ($data['programs'] as $row) {
            if (is_array($row) && in_array($row['slug'] ?? null, self::SLUGS, true)) {
                $bySlug[$row['slug']] = $row;
            }
        }

        $missing = array_values(array_diff(self::SLUGS, array_keys($bySlug)));

        if ($missing !== []) {
            throw new \RuntimeException('Missing programme rows: '.implode(', ', $missing));
        }

        // Keep the declared order, not the file order.
        return array_map(fn (string $slug) => $bySlug[$slug], self::SLUGS);
    }

    private function category(): ProgramCategory
    {
        return ProgramCategory::query()->firstOrCreate(
            ['slug' => self::CATEGORY['slug']],
            [
                'name' => self::CATEGORY['name'],
                'icon' => 'graduation-cap',
                'description' => self::CATEGORY['name'].' programmes.',
                'is_active' => true,
                'sort_order' => self::CATEGORY['sort_order'],
            ],
        );
    }

    private function university(): UniversityPartner
    {
        return UniversityPartner::query()->firstOrCreate(
            ['slug' => self::UNIVERSITY['slug']],
            [
                'name' => self::UNIVERSITY['name'],
                'country' => self::UNIVERSITY['country'],
                'country_code' => self::UNIVERSITY['country_code'],
                'is_active' => true,
                'sort_order' => self::UNIVERSITY['sort_order'],
            ],
        );
    }

    /** @param array<string, mixed> $row */
    private function program(array $row, ProgramCategory $category, UniversityPartner $university): Program
    {
        $program = Program::query()->firstOrNew(['slug' => $row['slug']]);
        $creating = ! $program->exists;

        // Never silently move an existing programme to another partner or
        // category; a slug collision should stop the run instead.
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
            'description' => $this->paragraphs($row['overview'] ?? []),
            'highlights' => $this->pairs($row['highlights'] ?? [], 'label', 'value'),
            'snapshot' => $this->pairs($row['snapshot'] ?? [], 'label', 'value'),
            'benefits' => array_map(fn (array $benefit) => [
                'title' => $benefit[0],
                'desc' => $this->richParagraph($benefit[1]),
                'icon' => $benefit[2],
            ], $row['benefits'] ?? []),
            'learning' => array_map(fn (string $item) => ['item' => $item], $row['learning'] ?? []),
            'careers' => array_map(fn (string $title) => ['title' => $title], $row['careers'] ?? []),
            'structure' => array_map(fn (array $stage) => [
                'title' => $stage['title'],
                'subtitle' => $stage['subtitle'] ?? '',
                'modules' => array_map(fn (string $module) => ['title' => $module], $stage['modules'] ?? []),
            ], $row['structure'] ?? []),
            'support' => array_map(fn (string $item) => ['item' => $item], $row['support'] ?? []),
            'gcc_heading' => $row['gcc_heading'] ?? 'Why GCC Students Choose This Programme',
            'gcc_reasons' => $row['gcc'] ?? [],
            'fees' => array_map(fn (string $title) => ['title' => $title], $row['fees'] ?? []),
            'sort_order' => (int) ($row['sort_order'] ?? 0),
            // These two pages are approved for publication, so activation is
            // explicit here rather than left to a manual admin toggle.
            'is_active' => true,
        ]);

        if ($creating) {
            $program->is_featured = false;
            $program->testimonials = [];
            $program->reviews = [];
        }

        $program->save();

        return $program;
    }

    /** @param array<string, mixed> $row */
    private function seo(Program $program, array $row): void
    {
        $seo = $program->seo()->firstOrNew([]);

        if (! $seo instanceof SeoMetadata) {
            throw new \RuntimeException("Unable to resolve SEO record: {$program->slug}");
        }

        // Only the authored copy is overwritten; a canonical URL or any other
        // admin-set SEO field survives a re-run.
        $seo->fill([
            'meta_title' => $row['meta_title'] ?? '',
            'meta_description' => $row['meta_description'] ?? '',
        ])->save();
    }

    /** @param list<array{0: string, 1: string, 2: int}> $faqs */
    private function faqs(Program $program, array $faqs): void
    {
        foreach ($faqs as $faqRow) {
            [$question, $answer] = $faqRow;
            $sort = (int) ($faqRow[2] ?? 0);

            $atSort = $program->faqs()->where('sort_order', $sort)->get();

            if ($atSort->count() > 1) {
                throw new \RuntimeException("Ambiguous FAQ slot {$sort} for programme: {$program->slug}");
            }

            $faq = $program->faqs()->where('question', $question)->first()
                ?? $atSort->first()
                ?? $program->faqs()->make();

            $faq->question = $question;
            $faq->answer = $answer;
            $faq->sort_order = $sort;

            if (! $faq->exists) {
                $faq->is_active = true;
            }

            $faq->save();
        }
    }

    /** @param list<string> $paragraphs */
    private function paragraphs(array $paragraphs): string
    {
        return collect($paragraphs)
            ->map(fn (string $paragraph) => '<p>'.$paragraph.'</p>')
            ->implode('');
    }

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
