<?php

namespace Database\Seeders;

use App\Models\Program;
use App\Support\PublicContentCache;

/**
 * One existing-reference-only content refresh: 67 approved catalogue rows
 * plus the legacy Master/BBA rows that already exist in the database.
 *
 * Never creates a category or partner. Missing legacy programmes are logged,
 * never inserted. UCA Global MBA and the extra UWS MBA are excluded pending
 * separate approval; their copy, titles and relationships are untouched.
 * Existing editorial toggles, media, testimonials, reviews, sort order and
 * non-copy SEO settings survive. All writes share the catalogue transaction.
 *
 * Staging:    php artisan db:seed --class=ExistingCatalogProgramsSeeder
 * Production: php artisan db:seed --class=ExistingCatalogProgramsSeeder --force
 */
class ExistingCatalogProgramsSeeder extends CatalogProgramsSeeder
{
    private const EXCLUDED_MASTERS = [
        'global-mba' => 'university-for-the-creative-arts',
        'mba-in-international-business-university-of-the-west-of-scotland' => 'university-of-the-west-of-scotland',
    ];

    protected function existingReferencesOnly(): bool
    {
        return true;
    }

    protected function expectedProgramCounts(): array
    {
        return [
            'doctorate_programs.php' => 13,
            'diploma_programs.php' => 49,
            'bachelors_programs.php' => 5,
        ];
    }

    public function run(): void
    {
        parent::run();

        // Programme model observers already clear public caches on save; this
        // also clears the listing after a successful no-op/idempotent re-run.
        PublicContentCache::forget(
            PublicContentCache::PROGRAMS_LISTING,
            PublicContentCache::FEATURED_PROGRAMS,
            PublicContentCache::NAVMENU_PROGRAMS,
        );
    }

    protected function afterCatalogProgramsSeeded(array $files): void
    {
        $master = require __DIR__.'/data/master_programs.php';
        $bbas = require __DIR__.'/data/rushford_bba.php';
        if (! is_array($master) || ! is_array($master['categories'] ?? null)
            || ! is_array($master['universities'] ?? null)
            || ! is_array($master['programs'] ?? null)
            || count($master['programs']) !== 50
            || ! is_array($bbas) || count($bbas) !== 7) {
            throw new \RuntimeException('Legacy programme data is missing or incomplete; catalogue transaction rolled back.');
        }

        $categorySlugs = array_column($master['categories'], 'slug');
        $partnerSlugs = array_column($master['universities'], 'slug');
        $seen = [];
        foreach ($files as $file) {
            foreach ($file['programs'] as $row) {
                $slug = $row['slug'] ?? null;
                if (! is_string($slug) || $slug === '' || isset($seen[$slug])) {
                    throw new \RuntimeException('Invalid or duplicate catalogue programme slug; transaction rolled back.');
                }
                $seen[$slug] = true;
            }
        }

        $legacy = [];
        $excluded = [];
        foreach ($master['programs'] as $row) {
            $this->validateLegacyRow($row, 'master_programs.php');
            if (! in_array($row['category_slug'], $categorySlugs, true)
                || ! in_array($row['university_slug'], $partnerSlugs, true)) {
                throw new \RuntimeException("Undeclared Master category or partner for: {$row['slug']}");
            }
            $slug = $row['slug'];
            if (isset($seen[$slug])) {
                throw new \RuntimeException("Duplicate programme slug across seed files: {$slug}");
            }
            $seen[$slug] = true;
            if (isset(self::EXCLUDED_MASTERS[$slug])) {
                if ($row['university_slug'] !== self::EXCLUDED_MASTERS[$slug]) {
                    throw new \RuntimeException("Excluded Master has an unexpected partner: {$slug}");
                }
                $excluded[] = $slug;
                continue;
            }
            $legacy[$slug] = $row;
        }
        if (count($excluded) !== count(self::EXCLUDED_MASTERS)) {
            throw new \RuntimeException('The 2 excluded Master identities were not both found; transaction rolled back.');
        }

        foreach ($bbas as $row) {
            $this->validateLegacyRow($row, 'rushford_bba.php');
            $slug = $row['slug'];
            if (isset($seen[$slug])) {
                throw new \RuntimeException("Duplicate programme slug across seed files: {$slug}");
            }
            $seen[$slug] = true;
            $legacy[$slug] = [
                ...$row,
                'category_slug' => 'bachelors',
                'university_slug' => 'rushford-business-school',
                'duration' => '20 to 24 months',
                'level' => 'BBA',
            ];
        }

        // Preflight every present legacy row before modifying any of them.
        // A wrong partner/category fails the entire transaction, including
        // catalogue changes made above; missing rows are never fabricated.
        $existing = Program::query()
            ->with(['programCategory:id,slug', 'universityPartner:id,slug'])
            ->whereIn('slug', array_keys($legacy))
            ->get()
            ->keyBy('slug');
        $updates = [];
        $missing = [];
        foreach ($legacy as $slug => $row) {
            $program = $existing->get($slug);
            if (! $program) {
                $missing[] = $slug;
                continue;
            }
            if ($program->programCategory?->slug !== $row['category_slug']
                || $program->universityPartner?->slug !== $row['university_slug']) {
                throw new \RuntimeException("Refusing to reassign existing legacy programme: {$slug}");
            }
            if ($program->title !== $row['title'] || $program->level !== $row['level']) {
                throw new \RuntimeException("Refusing to change existing legacy identity: {$slug}");
            }
            $updates[] = [$program, $row];
        }

        foreach ($updates as [$program, $row]) {
            $this->refreshLegacyCopy($program, $row);
        }

        $this->command?->info('Updated '.count($updates).' existing legacy programmes; excluded 2 unapproved Master rows.');
        if ($missing !== []) {
            $this->command?->warn('Skipped '.count($missing).' missing legacy programmes (none created): '.implode(', ', $missing));
        }
    }

    private function validateLegacyRow(mixed $row, string $source): void
    {
        if (! is_array($row)) {
            throw new \RuntimeException("Invalid legacy programme row in {$source}");
        }
        $required = ['slug', 'title', 'hero', 'meta_title', 'meta_description'];
        if ($source === 'master_programs.php') {
            array_push($required, 'category_slug', 'university_slug', 'level', 'duration');
        }
        foreach ($required as $field) {
            if (! is_string($row[$field] ?? null) || $row[$field] === '') {
                throw new \RuntimeException("Missing {$field} in {$source}");
            }
        }
        foreach (['overview', 'highlights', 'snapshot', 'benefits', 'learning', 'careers', 'structure', 'support', 'gcc', 'fees'] as $field) {
            if (! is_array($row[$field] ?? null)) {
                throw new \RuntimeException("Missing {$field} in {$source}: {$row['slug']}");
            }
        }
    }

    /** Copy fields only: never reset activation, feature, media or admin metadata. */
    private function refreshLegacyCopy(Program $program, array $row): void
    {
        $program->fill([
            'duration' => $row['duration'],
            'short_description' => $row['hero'],
            'description' => collect($row['overview'])
                ->map(fn (string $paragraph) => '<p>'.$paragraph.'</p>')->implode(''),
            'highlights' => $this->pairs($row['highlights'], 'label', 'value'),
            'snapshot' => $this->pairs($row['snapshot'], 'label', 'value'),
            'benefits' => array_map(fn (array $benefit) => [
                'title' => $benefit[0],
                'desc' => $benefit[1] !== '' ? '<p>'.$benefit[1].'</p>' : '',
                'icon' => $benefit[2],
            ], $row['benefits']),
            'learning' => array_map(fn (string $item) => ['item' => $item], $row['learning']),
            'careers' => array_map(fn (string $title) => ['title' => $title], $row['careers']),
            'structure' => array_map(fn (array $stage) => [
                'title' => $stage['title'],
                'subtitle' => $stage['subtitle'] ?? '',
                'modules' => array_map(function (array|string $module) {
                    if (is_string($module)) {
                        return ['title' => $module];
                    }
                    $mapped = ['title' => $module['title']];
                    if (filled($module['overview'] ?? null)) {
                        $mapped['overview'] = '<p>'.$module['overview'].'</p>';
                    }

                    return $mapped;
                }, $stage['modules']),
            ], $row['structure']),
            'support' => array_map(fn (string $item) => ['item' => $item], $row['support']),
            'gcc_reasons' => $row['gcc'],
            'fees' => array_map(fn (string $title) => ['title' => $title], $row['fees']),
        ]);
        $program->save();

        $seo = $program->seo()->firstOrNew([]);
        $seo->fill([
            'meta_title' => $row['meta_title'],
            'meta_description' => $row['meta_description'],
        ]);
        $seo->save();
    }

    /** @return list<array<string, string>> */
    private function pairs(array $rows, string $left, string $right): array
    {
        return array_map(fn (array $row) => [$left => $row[0], $right => $row[1]], $rows);
    }
}
